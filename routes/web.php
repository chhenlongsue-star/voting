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
        ->sortByDesc(fn($poll) => $poll->options->sum('votes_count'))
        ->first();

    return view('welcome', compact('trendingPoll'));
});

/*
|--------------------------------------------------------------------------
| Google Social Authentication
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
                'avatar' => $googleUser->getAvatar(),
                'password' => $existingUser->password ?? bcrypt(Str::random(24)),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user, true); // Added 'true' to remember the session
        return redirect()->intended('/dashboard');

    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Google authentication failed.');
    }
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Logic
    Route::get('/dashboard', function () {
        $polls = Poll::with(['options' => function ($query) {
            $query->withCount('votes');
        }])
        ->where('is_active', true)
        ->latest()
        ->get()
        ->map(function ($poll) {
            $poll->user_has_voted = $poll->options()->whereHas('votes', function ($query) {
                $query->where('user_id', auth()->id());
            })->exists();
            return $poll;
        });

        return view('dashboard', compact('polls'));
    })->name('dashboard');
    
    // The Vote Submission Route (Strictly within Auth Group)
    Route::post('/polls/{poll}/vote', [VoteController::class, 'store'])->name('votes.store');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin.access'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::delete('polls/{poll}/options/{option}', [PollController::class, 'destroyOption'])->name('polls.options.destroy');
    Route::resource('polls', PollController::class);
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';
