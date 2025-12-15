<?php

namespace App\Http\Controllers;

use App\Models\Prototype;
use Illuminate\Http\Request;

class PrototypeController extends Controller
{
    public function create()
    {
        return view('Prototype.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
        ]);

        Prototype::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Prototipe berhasil ditambahkan 🚀');
    }

    public function destroy(Prototype $prototype)
    {
        // Pastikan user pemilik data
        if ($prototype->user_id !== auth()->id()) {
            abort(403);
        }

        $prototype->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Prototipe berhasil dihapus 🗑️');
    }
}
