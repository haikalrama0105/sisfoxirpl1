<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(): View
    {
        $siswas = Siswa::with('kelas')
            ->orderBy('nama')
            ->paginate(15);

        return view('siswa.index', compact('siswas'));
    }

    public function create(): View
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.create', compact('kelases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:255', 'unique:siswas,nis'],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:255'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa): View
    {
        $siswa->load(['kelas', 'nilais.mapel']);

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa): View
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.edit', compact('siswa', 'kelases'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:255', 'unique:siswas,nis,'.$siswa->id],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:255'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $siswa->update($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
