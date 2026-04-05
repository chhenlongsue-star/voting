<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\Option;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    /**
     * Display a listing of the polls with Search and Filter.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        $query = Poll::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        $polls = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.polls.index', compact('polls', 'categories'));
    }

    /**
     * Show the form for creating a new poll.
     */
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        $categories = Category::all();
        return view('admin.polls.create', compact('categories'));
    }

    /**
     * Store a newly created poll.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        $validated = $request->validate([
            'question'    => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'options'     => 'required|array|min:2', 
            'options.*'   => 'required|string|max:255',
            'is_active'   => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $poll = Poll::create([
                'question'    => $validated['question'],
                'category_id' => $validated['category_id'],
                'is_active'   => $request->has('is_active') ? $request->is_active : true,
            ]);

            foreach ($validated['options'] as $optionText) {
                $poll->options()->create(['option_text' => $optionText]);
            }
        });

        return redirect()->route('admin.polls.index')->with('success', 'Poll created successfully!');
    }

    /**
     * Display Poll Analytics: Latest, Trending, and Categories.
     */
    public function show(Poll $poll)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        // 1. Current Poll Details
        $poll->load(['category', 'options']);

        // 2. Latest Polls (Main Phase - Center Column)
        $latestPolls = Poll::with('category')
            ->latest()
            ->take(10)
            ->get();

        // 3. Trending Polls (Right Sidebar 1)
        // withSum calculates total votes across all options for that poll
        $trendingPolls = Poll::with('category')
            ->withSum('options as total_votes', 'votes_count')
            ->orderByDesc('total_votes')
            ->take(5)
            ->get();

        // 4. All Categories (Right Sidebar 2 / Dropdown)
        $categories = Category::all();

        return view('admin.polls.show', compact('poll', 'latestPolls', 'trendingPolls', 'categories'));
    }

    /**
     * Show the form for editing.
     */
    public function edit(Poll $poll)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        $poll->load('options');
        $categories = Category::all();
        
        return view('admin.polls.edit', compact('poll', 'categories'));
    }

    /**
     * Update the poll and its options.
     */
    public function update(Request $request, Poll $poll)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        $validated = $request->validate([
            'question'                        => 'required|string|max:255',
            'category_id'                     => 'required|exists:categories,id',
            'is_active'                       => 'required|boolean',
            'existing_options'                => 'required|array',
            'existing_options.*.option_text'  => 'required|string|max:255',
            'new_options'                     => 'nullable|array',
            'new_options.*'                   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $poll) {
            $poll->update([
                'question'    => $validated['question'],
                'category_id' => $validated['category_id'],
                'is_active'   => $validated['is_active'],
            ]);

            foreach ($validated['existing_options'] as $id => $optionData) {
                $poll->options()->where('id', $id)->update($optionData);
            }

            if (!empty($validated['new_options'])) {
                foreach ($validated['new_options'] as $newOption) {
                    if (!empty(trim($newOption))) {
                        $poll->options()->create(['option_text' => $newOption]);
                    }
                }
            }
        });

        return redirect()->route('admin.polls.index')->with('success', 'Poll updated successfully!');
    }

    /**
     * Delete the poll.
     */
    public function destroy(Poll $poll)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        $poll->delete();
        return redirect()->route('admin.polls.index')->with('success', 'Poll deleted successfully!');
    }

    /**
     * Delete a specific option.
     */
    public function destroyOption(Poll $poll, Option $option)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Admin only');
        }

        if ($option->poll_id !== $poll->id) {
            abort(404);
        }

        $option->delete();
        return redirect()->back()->with('success', 'Option deleted successfully!');
    }
}