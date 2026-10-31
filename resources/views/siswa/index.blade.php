@extends('layouts.main')

@section('title', 'Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-graduate mr-2 text-primary"></i>Data Siswa</h1>
            <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Tambah Siswa</a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Jenis Kelamin</th><th>No HP</th><th>Alamat</th><th class="text-center">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $index => $siswa)
                                <tr>
                                    <td>{{ $siswas->firstItem() + $index }}</td><td>{{ $siswa->nis }}</td><td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->kelas->nama_kelas }}</td><td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $siswa->no_hp ?: '-' }}</td><td>{{ $siswa->alamat ?: '-' }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('siswa.show', $siswa) }}" class="btn btn-sm btn-info" aria-label="Detail siswa"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-sm btn-warning" aria-label="Edit siswa"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" aria-label="Hapus siswa"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data siswa.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $siswas->links() }}
            </div>
        </div>
    </div>
@endsection
