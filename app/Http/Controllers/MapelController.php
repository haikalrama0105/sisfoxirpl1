<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapelController extends Controller
{
    public function index(): View
    {
        $mapels = Mapel::query()
            ->orderBy('nama_mapel')
            ->paginate(15);

        return view('mapel.index', compact('mapels'));
    }

    public function create(): View
    {
        return view('mapel.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:255', 'unique:mapels,kode_mapel'],
            'nama_mapel' => ['required', 'string', 'max:255'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);

        Mapel::create($data);

        return redirect()->route('mapel.index')->with('success', 'Data mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel): View
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel): RedirectResponse
    {
        $data = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:255', 'unique:mapels,kode_mapel,'.$mapel->id],
            'nama_mapel' => ['required', 'string', 'max:255'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);

        $mapel->update($data);

        return redirect()->route('mapel.index')->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel): RedirectResponse
    {
        if ($mapel->nilais()->exists()) {
            return redirect()->route('mapel.index')->with('error', 'Mata pelajaran tidak dapat dihapus karena masih memiliki data nilai.');
        }

        $mapel->delete();

        return redirect()->route('mapel.index')->with('success', 'Data mata pelajaran berhasil dihapus.');
    }
}
