<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Http\Controllers\VoteController;

// Put this inside the 'auth' middleware group
Route::post('/polls/{poll}/vote', [VoteController::class, 'store'])->name('polls.vote');
