<?php

namespace App\Http\Controllers;

use App\Models\Prototype;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $start = Carbon::now()->subDays(6)->startOfDay();
        $end   = Carbon::now()->endOfDay();

        $prototypes = Prototype::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with(['dailyLogs' => function ($q) use ($start, $end) {
                $q->whereBetween('logged_at', [$start, $end]);
            }])
            ->get();

        // ⬇️ TAMBAH PROPERTY TANPA JADI ARRAY
        foreach ($prototypes as $prototype) {
            $totalDays = 7;
            $successCount = $prototype->dailyLogs->where('success', true)->count();

            $prototype->success_rate = round(($successCount / $totalDays) * 100);

            // Cek apakah hari ini sudah check-in
            $prototype->today_log = $prototype->dailyLogs->first(function ($log) {
                return $log->logged_at->isToday();
            });
        }

        return view('dashboard', compact('prototypes'));
    }

}
