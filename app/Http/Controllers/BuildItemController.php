<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\BuildItem;
use Illuminate\Http\Request;

class BuildItemController extends Controller
{
    public function submit(Request $request, Build $build, BuildItem $item)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $item->submitted_amount += $request->amount;
        if ($item->submitted_amount > $item->amount) {
            $item->submitted_amount = $item->amount;
        }
        $item->save();

        return response()->json([
            'success' => true,
            'submitted_amount' => $item->submitted_amount,
            'required_amount' => $item->amount,
        ]);
    }
}
