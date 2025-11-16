<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessStatus;
use App\Models\Nomination; // Added
use App\Http\Controllers\ProcessController; // Added
use Carbon\Carbon;

class AdminController extends Controller
{
    // Added index method for nominations
    public function index()
    {
        // Fetch pending nominations and group them by category
        $nominationsByCategory = Nomination::with('category')->whereHas('category')
            ->where('status', 'pending')
            ->get()
            ->groupBy('category.name');
        return view('admin.nominations.index', compact('nominationsByCategory'));
    }

    public function startProcess(Request $request)
    {
        return app(ProcessController::class)->startNomination($request);
    }

    // Added approve method
    public function approve(Nomination $nomination)
    {
        $nomination->status = 'approved';
        $nomination->save();
        return redirect()->back()->with('success', 'Nomination approved successfully!');
    }

    // Added reject method
    public function reject(Nomination $nomination)
    {
        $nomination->status = 'rejected';
        $nomination->save();
        return redirect()->back()->with('success', 'Nomination rejected successfully!');
    }

    // Added show method
    public function adminDashboard()
    {
        $processStatus = ProcessStatus::firstOrNew([]);
        return view('admin.dashboard', compact('processStatus'));
    }

    public function show(Nomination $nomination)
    {
        return view('admin.nominations.show', compact('nomination'));
    }

    public function startNomination()
    {
        return app(ProcessController::class)->startNomination(new Request());
    }

    // Added stopProcess method to fix the error
    public function stopProcess()
    {
        return app(ProcessController::class)->stop();
    }
}
