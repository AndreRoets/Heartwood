<?php

namespace App\Http\Controllers;

use App\Models\Nomination;
use App\Models\ProcessStatus;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProcessController extends Controller
{
    public function startNomination(Request $request)
    {
        $processStatus = ProcessStatus::firstOrCreate([]);

        $processStatus->update([
            'status' => 'nominating',
            'nomination_starts_at' => Carbon::now('UTC'),
            'nomination_ends_at' => Carbon::now('UTC')->addDays(3),
            'voting_starts_at' => Carbon::now('UTC')->addDays(3),
            'voting_ends_at' => Carbon::now('UTC')->addDays(6), // Nomination (3) + Voting (3) = 6 days total
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Nomination process has been started.');
    }

    public function startVoting()
    {
        $processStatus = ProcessStatus::firstOrCreate([]);

        $processStatus->update([
            'status'               => 'voting',
            'nomination_ends_at'   => Carbon::now('UTC'), // End nomination period now
            'voting_starts_at'     => Carbon::now('UTC'), // Start voting period now
            'voting_ends_at'       => Carbon::now('UTC')->addDays(3), // Set voting to end 3 days from now
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Voting process has been started.');
    }

    public function showResults()
    {
        $processStatus = ProcessStatus::firstOrCreate([]);

        // End the voting period and mark the process as completed.
        $processStatus->update([
            'status'         => 'completed',
            'voting_ends_at' => Carbon::now('UTC'),
        ]);

        return redirect()->route('nominations.results');
    }

    public function stop()
    {
        // 1. Delete all nomination images from storage
        $nominations = Nomination::all();
        foreach ($nominations as $nomination) {
            if (!empty($nomination->images)) {
                foreach ($nomination->images as $imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        // 2. Delete all nominations
        Nomination::query()->delete();

        // 3. Delete all votes
        Vote::query()->delete();

        // 4. Reset the process status
        $processStatus = ProcessStatus::firstOrNew([]);
        if ($processStatus) {
            $processStatus->update([
                'status' => 'inactive',
                'nomination_starts_at' => null,
                'nomination_ends_at' => null,
                'voting_starts_at' => null,
                'voting_ends_at' => null,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'All processes have been stopped. Nominations and votes have been cleared.');
    }
}