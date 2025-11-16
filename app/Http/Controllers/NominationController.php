<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Nomination;
use App\Models\Vote;
use App\Models\ProcessStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NominationController extends Controller
{
    public function index()
    {
        $processStatus = ProcessStatus::firstOrNew([]); // Fetch the process status
        $categories = Category::all(); // Fetch all categories
        $user = Auth::user();
        $votedCategories = [];

        if ($user) {
            $votedCategories = Vote::where('user_id', $user->id)
                ->pluck('category_id')
                ->unique()
                ->toArray();
        }

        return view('nominations.index', compact('categories', 'processStatus', 'votedCategories'));
    }

    public function showCategoryNominations(Category $category)
    {
        $now = Carbon::now();
        $processStatus = ProcessStatus::firstOrNew([]); // The model accessor will derive the correct status
        $nominations = Nomination::where('category_id', $category->id)
                        ->where('status', 'approved')->get();

        return view('nominations.category_nominations', compact('nominations', 'category', 'processStatus'));
    }

    public function create()
    {
        $categories = Category::all();
        $processStatus = ProcessStatus::firstOrNew([]); // Fetch the process status
        $now = Carbon::now();

        if (!$processStatus || $processStatus->status !== 'nominating' || !$now->between($processStatus->nomination_starts_at, $processStatus->nomination_ends_at)) {
            return redirect()->route('home')->with('error', 'Nominations are not currently open.');
        }

        return view('nominations.create', compact('categories', 'processStatus'));
    }

    public function store(Request $request)
    {
        $category = Category::findOrFail($request->category_id);

        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ];

        if ($category->allow_image_uploads) {
            $rules['images'] = 'array|max:3';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'; // Individual image validation
        }

        $request->validate($rules);

        $processStatus = ProcessStatus::firstOrNew([]);
        $now = Carbon::now();

        if ($processStatus && $processStatus->status == 'nominating' && $now->between($processStatus->nomination_starts_at, $processStatus->nomination_ends_at)) {
            $nomination = Nomination::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'title' => $request->name,
                'description' => $request->description,
                'nomination_starts_at' => $processStatus->nomination_starts_at,
                'nomination_ends_at' => $processStatus->nomination_ends_at,
                'voting_starts_at' => $processStatus->voting_starts_at,
                'voting_ends_at' => $processStatus->voting_ends_at,
            ]);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('nomination_images', 'public');
                    $imagePaths[] = $path;
                }
            }

            $nomination->images = $imagePaths;
            $nomination->save();

            return redirect()->route('nominations.create')->with('success', 'Nomination submitted successfully!');
        } else {
            return redirect()->route('nominations.create')->with('error', 'Nominations are not open at this time.');
        }
    }

    public function vote(Nomination $nomination)
    {
        $processStatus = ProcessStatus::firstOrNew([]);
        $now = Carbon::now();

        // Defensive check to prevent TypeError if dates are null
        if (!$processStatus->voting_starts_at || !$processStatus->voting_ends_at) {
            return redirect()->back()->with('error', 'Voting period is not properly configured. Please contact an administrator.');
        }

        if ($processStatus->status !== 'voting' || !$now->between($processStatus->voting_starts_at, $processStatus->voting_ends_at)) {
            return redirect()->back()->with('error', 'Voting is not currently open.');
        }

        $user = Auth::user();

        if (is_null($nomination->category_id)) {
            return redirect()->back()->with('error', 'Nomination category is missing.');
        }

        // Check if the user has already voted in this category
        $existingCategoryVote = Vote::where('user_id', $user->id)
            ->where('category_id', $nomination->category_id)
            ->first();

        if ($existingCategoryVote) {
            return redirect()->back()->with('error', 'You have already voted in this category.');
        }

        // Check if the user has already voted for this specific nomination
        $existingNominationVote = Vote::where('user_id', $user->id)
            ->where('nomination_id', $nomination->id)
            ->first();

        if ($existingNominationVote) {
            return redirect()->back()->with('error', 'You have already voted for this nomination.');
        }


        Vote::create([
            'user_id' => $user->id,
            'nomination_id' => $nomination->id,
            'category_id' => $nomination->category_id,
        ]);

        return redirect()->route('nominations.index')->with('success', 'Your vote has been cast successfully!');
    }

    public function adminIndex()
    {
        // Fetch pending nominations and group them by category
        $nominationsByCategory = Nomination::with('category', 'user')
            ->where('status', 'pending')
            ->get()
            ->groupBy('category.name');
        return view('admin.nominations.index', compact('nominationsByCategory'));
    }

    public function approve(Nomination $nomination)
    {
        $nomination->update(['status' => 'approved']);
        return redirect()->route('admin.nominations.index')->with('success', 'Nomination approved.');
    }

    public function reject(Nomination $nomination)
    {
        $nomination->update(['status' => 'rejected']);
        return redirect()->route('admin.nominations.index')->with('success', 'Nomination rejected.');
    }

    public function results()
    {
        $processStatus = ProcessStatus::firstOrNew([]);

        // Only show results if the process is completed.
        if ($processStatus->status !== 'completed') {
            return redirect()->route('nominations.index')->with('error', 'Results are not available until voting has ended.');
        }

        $categories = Category::with(['nominations' => function ($query) {
            $query->where('status', 'approved')
                  ->withCount('votes')
                  ->orderByDesc('votes_count');
        }])->get();

        $categoryWinners = [];
        foreach ($categories as $category) {
            $winner = $category->nominations->first(); // The first nomination after ordering is the winner
            $categoryWinners[] = [
                'category' => $category,
                'winner' => $winner,
            ];
        }

        return view('nominations.results', compact('categoryWinners', 'processStatus'));
    }
}