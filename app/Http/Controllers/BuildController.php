<?php

namespace App\Http\Controllers;

use App\Models\Build;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class BuildController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $builds = Build::with('user')->latest()->get();
        return view('builds.index', compact('builds'));
    }

    public function create()
    {
        return view('builds.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'coordinates' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.identifier' => 'required|string',
            'items.*.amount' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $build = Build::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'description' => $validated['description'],
                'coordinates' => $validated['coordinates'],
            ]);
    
            foreach ($validated['items'] as $item) {
                $build->items()->create([
                    'item_identifier' => $item['identifier'],
                    'amount' => $item['amount'],
                ]);
            }
        });

        return redirect()->route('builds.index')->with('success', 'Build submitted successfully.');
    }

    public function show(Build $build)
    {
        return view('builds.show', compact('build'));
    }

    public function destroy(Build $build)
    {
        $this->authorize('delete', $build);

        $build->delete();

        return redirect()->route('builds.index')->with('success', 'Build deleted successfully.');
    }
}
