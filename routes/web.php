<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\VoteController;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $trendingPoll = Poll::with(['options' => function ($query) {
            $query->withCount('votes');
        }])
        ->where('is_active', true)
        ->get()
        ->sortByDesc(function($poll) {
            return $poll->options->sum('votes_count');
        })
        ->first();

    return view('welcome', compact('trendingPoll'));
});

/*
|--------------------------------------------------------------------------
| Google Social Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', function () {
    return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();
        
        $existingUser = User::where('email', $googleUser->getEmail())->first();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(), // Stores Gmail profile pic
                'password' => $existingUser->password ?? bcrypt(Str::random(24)),
                'email_verified_at' => now(), 
            ]
        );

        Auth::login($user);
        return redirect()->intended('/dashboard');

    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Google authentication failed.');
    }
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Dashboard, Profile, Voting)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        $polls = Poll::with(['options' => function ($query) {
            $query->withCount('votes');
        }])
        ->where('is_active', true)
        ->get()
        ->map(function ($poll) {
            $poll->user_has_voted = $poll->options()->whereHas('votes', function ($query) {
                $query->where('user_id', auth()->id());
            })->exists();
            return $poll;
        });

        return view('dashboard', compact('polls'));
    })->name('dashboard');

    Route::post('/polls/{poll}/vote', [VoteController::class, 'store'])->name('polls.vote');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected by AdminAccess Middleware)
|--------------------------------------------------------------------------
*/
// 'admin.access' checks both is_admin and role columns
Route::middleware(['auth', 'admin.access'])->prefix('admin')->name('admin.')->group(function () {
    
    // User Management
    Route::resource('users', UserController::class);

    // Specific route for deleting a single option
    Route::delete('polls/{poll}/options/{option}', [PollController::class, 'destroyOption'])->name('polls.options.destroy');

    // Standard Resource Routes for Polls
    Route::resource('polls', PollController::class);

    // Standard Resource Routes for Categories
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';