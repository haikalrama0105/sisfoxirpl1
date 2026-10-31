<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(): View
    {
        $nilais = Nilai::with(['siswa.kelas', 'mapel'])->latest()->paginate(15);

        return view('nilai.index', compact('nilais'));
    }

    public function create(): View
    {
        $siswas = Siswa::with('kelas')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();

        return view('nilai.create', compact('siswas', 'mapels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->saveNilai($data);

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil ditambahkan.');
    }

    public function edit(Nilai $nilai): View
    {
        $siswas = Siswa::with('kelas')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();

        return view('nilai.edit', compact('nilai', 'siswas', 'mapels'));
    }

    public function update(Request $request, Nilai $nilai): RedirectResponse
    {
        $data = $this->validatedData($request, $nilai);
        $this->saveNilai($data, $nilai);

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil dihapus.');
    }

    /**
     * @return array{siswa_id: string|int, mapel_id: string|int, nilai_tugas: string|int|float, nilai_uts: string|int|float, nilai_uas: string|int|float}
     */
    private function validatedData(Request $request, ?Nilai $nilai = null): array
    {
        $mapelId = is_numeric($request->input('mapel_id'))
            ? (int) $request->input('mapel_id')
            : 0;
        $uniqueSiswaRule = Rule::unique('nilais', 'siswa_id')
            ->where('mapel_id', $mapelId);

        if ($nilai !== null) {
            $uniqueSiswaRule->ignore($nilai->id);
        }

        return $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:siswas,id', $uniqueSiswaRule],
            'mapel_id' => ['required', 'integer', 'exists:mapels,id'],
            'nilai_tugas' => ['required', 'numeric', 'between:0,100'],
            'nilai_uts' => ['required', 'numeric', 'between:0,100'],
            'nilai_uas' => ['required', 'numeric', 'between:0,100'],
        ]);
    }

    /**
     * @param  array{siswa_id: string|int, mapel_id: string|int, nilai_tugas: string|int|float, nilai_uts: string|int|float, nilai_uas: string|int|float}  $data
     */
    private function saveNilai(array $data, ?Nilai $nilai = null): void
    {
        $nilaiAkhir = round(
            ((float) $data['nilai_tugas'] * 0.3)
                + ((float) $data['nilai_uts'] * 0.3)
                + ((float) $data['nilai_uas'] * 0.4),
            2
        );
        $mapel = Mapel::findOrFail($data['mapel_id']);

        $attributes = [
            ...$data,
            'nilai_akhir' => $nilaiAkhir,
            'keterangan' => $nilaiAkhir >= $mapel->kkm ? 'Lulus' : 'Tidak Lulus',
        ];

        if ($nilai === null) {
            Nilai::create($attributes);

            return;
        }

        $nilai->update($attributes);
    }
}
