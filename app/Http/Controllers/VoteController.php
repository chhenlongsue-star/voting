<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Vote; // <--- This is the missing piece!
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function store(Request $request, Poll $poll)
    {
        // 1. Validation
        $request->validate([
            'option_id' => 'required|exists:options,id',
        ]);

        // 2. Check if the user already voted (To prevent cheating!)
        // This query checks if a row exists with this user and this poll
        $alreadyVoted = Vote::where('user_id', Auth::id())
                            ->where('poll_id', $poll->id)
                            ->exists();

        if ($alreadyVoted) {
            return back()->with('error', 'You have already voted in this poll!');
        }

        // 3. Save the vote
        Vote::create([
            'user_id' => Auth::id(),
            'poll_id' => $poll->id,
            'option_id' => $request->option_id,
        ]);

        return back()->with('success', 'Vote recorded! Thank you.');
    }
}