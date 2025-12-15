<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'prototype_id' => 'required|exists:prototypes,id',
            'success' => 'required|boolean',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $today = Carbon::today();

        // ❌ Cegah double check-in hari yang sama
        $alreadyLogged = DailyLog::where('prototype_id', $request->prototype_id)
            ->whereDate('logged_at', $today)
            ->exists();

        if ($alreadyLogged) {
            return back()->with('error', 'Hari ini sudah check-in ⚠️');
        }

        $log = new DailyLog();
        $log->prototype_id = $request->prototype_id;
        $log->user_id = auth()->id();
        $log->success = $request->success;
        $log->quantity = $request->quantity;
        $log->logged_at = Carbon::now();
        $log->save();


        return back()->with('success', 'Check-in berhasil ✅');
    }
}
