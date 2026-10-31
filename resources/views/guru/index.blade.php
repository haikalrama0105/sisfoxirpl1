@extends('layouts.main')

@section('title', 'Data Guru')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chalkboard-teacher mr-2 text-primary"></i>Data Guru</h1>
            <a href="{{ route('guru.create') }}" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Tambah Guru</a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Guru</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white"><tr><th>No</th><th>NIP</th><th>Nama</th><th>Jenis Kelamin</th><th>Email</th><th>No HP</th><th>Alamat</th><th class="text-center">Aksi</th></tr></thead>
                        <tbody>
                            @forelse ($gurus as $index => $guru)
                                <tr>
                                    <td>{{ $gurus->firstItem() + $index }}</td><td>{{ $guru->nip }}</td><td>{{ $guru->nama }}</td>
                                    <td>{{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td><td>{{ $guru->email ?: '-' }}</td>
                                    <td>{{ $guru->no_hp ?: '-' }}</td><td>{{ $guru->alamat ?: '-' }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('guru.edit', $guru) }}" class="btn btn-sm btn-warning" aria-label="Edit guru"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('guru.destroy', $guru) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" aria-label="Hapus guru"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data guru.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $gurus->links() }}
            </div>
        </div>
    </div>
@endsection
