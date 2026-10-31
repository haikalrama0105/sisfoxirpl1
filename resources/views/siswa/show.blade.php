@extends('layouts.main')

@section('title', 'Detail Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-graduate mr-2 text-primary"></i>Detail Siswa</h1>
            <a href="{{ route('siswa.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Profil Siswa</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>NIS:</strong> {{ $siswa->nis }}</p>
                        <p><strong>Nama:</strong> {{ $siswa->nama }}</p>
                        <p><strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Jenis Kelamin:</strong> {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        <p><strong>No HP:</strong> {{ $siswa->no_hp ?: '-' }}</p>
                        <p><strong>Alamat:</strong> {{ $siswa->alamat ?: '-' }}</p>
                    </div>
                </div>
                <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-warning"><i class="fas fa-edit mr-1"></i> Edit Siswa</a>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Riwayat Nilai</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white"><tr><th>Mata Pelajaran</th><th>Tugas</th><th>UTS</th><th>UAS</th><th>Nilai Akhir</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse ($siswa->nilais as $nilai)
                                <tr><td>{{ $nilai->mapel->nama_mapel }}</td><td>{{ $nilai->nilai_tugas }}</td><td>{{ $nilai->nilai_uts }}</td><td>{{ $nilai->nilai_uas }}</td><td>{{ $nilai->nilai_akhir }}</td><td>{{ $nilai->keterangan }}</td></tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada nilai untuk siswa ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
