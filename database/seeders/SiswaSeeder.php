<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = Kelas::where('nama_kelas', 'XI RPL 1')->firstOrFail();

        Siswa::firstOrCreate(['nis' => '1001'], [
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
        ]);

        Siswa::firstOrCreate(['nis' => '1002'], [
            'nama' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ]);
    }
}
