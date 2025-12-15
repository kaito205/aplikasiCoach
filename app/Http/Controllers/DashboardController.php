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
        // 1️⃣ Ubah rentang waktu menjadi Minggu Ini (Senin - Minggu)
        $start = Carbon::now()->startOfWeek(); // Senin minggu ini
        $end   = Carbon::now()->endOfWeek();   // Minggu minggu ini

        $prototypes = Prototype::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with(['dailyLogs' => function ($q) use ($start, $end) {
                $q->whereBetween('logged_at', [$start, $end]);
            }])
            ->get();

        foreach ($prototypes as $prototype) {
            // Hitung success rate berdasarkan hari yang sudah berlalu (termasuk hari ini)
            // Agar persentase adil, pembaginya adalah jumlah hari yang sudah lewat di minggu ini, bukan selalu 7
            $daysPassed = Carbon::now()->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
            $successCount = $prototype->dailyLogs->where('success', true)->count();
            
            // Hindari pembagian dengan 0 (walau jarang terjadi karena dayOfWeekIso minimal 1)
            $prototype->success_rate = ($daysPassed > 0) 
                ? round(($successCount / $daysPassed) * 100) 
                : 0;

            // Property untuk cek hari ini (seperti sebelumnya)
            $prototype->today_log = $prototype->dailyLogs->first(function ($log) {
                return $log->logged_at->isToday();
            });

            // 2️⃣ Siapkan data untuk tampilan Senin - Minggu
            $weekly_progress = [];
            $currentDate = $start->copy();

            // Loop 7 hari (Senin - Minggu)
            for ($i = 0; $i < 7; $i++) {
                $dateStr = $currentDate->format('Y-m-d');
                $isToday = $currentDate->isToday();
                $isFuture = $currentDate->isFuture();

                // Cari log untuk tanggal ini
                $log = $prototype->dailyLogs->first(function ($l) use ($dateStr) {
                    return $l->logged_at->format('Y-m-d') === $dateStr;
                });

                $status = 'none'; // Default: belum isi (abu-abu)
                
                if ($log) {
                    $status = $log->success ? 'success' : 'failed'; // Hijau / Merah
                } elseif ($isFuture) {
                    $status = 'future'; // Masa depan (transparan/dashed)
                }

                $weekly_progress[] = [
                    'day_name' => $currentDate->locale('id')->isoFormat('dd'), // Sen, Sel, Rab...
                    'date' => $dateStr,
                    'status' => $status,
                    'is_today' => $isToday
                ];

                $currentDate->addDay();
            }

            $prototype->weekly_progress = $weekly_progress;

            // 3️⃣ Saran Analisis & Iterasi (Simple AI Logic)
            $prototype->suggestion = null;
            if ($daysPassed >= 3) { // Hanya beri saran jika sudah berjalan minimal 3 hari
                if ($prototype->success_rate >= 80) {
                    $prototype->suggestion = [
                        'type' => 'upgrade',
                        'message' => '🔥 Konsistensi Tinggi (>80%). Coba tingkatkan tantangannya!'
                    ];
                } elseif ($prototype->success_rate <= 50) {
                    $prototype->suggestion = [
                        'type' => 'downgrade',
                        'message' => '🛑 Konsistensi Rendah (<50%). Coba sederhanakan targetnya!'
                    ];
                }
            }
        }

        return view('dashboard', compact('prototypes'));
    }

}
