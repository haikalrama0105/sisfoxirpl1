@extends('layouts.main')

@section('title', 'Data Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book mr-2 text-primary"></i>Data Mata Pelajaran</h1>
            <a href="{{ route('mapel.create') }}" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm mr-1"></i> Tambah Mapel</a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Mata Pelajaran</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white"><tr><th>No</th><th>Kode Mapel</th><th>Nama Mapel</th><th>KKM</th><th class="text-center">Aksi</th></tr></thead>
                        <tbody>
                            @forelse ($mapels as $index => $mapel)
                                <tr>
                                    <td>{{ $mapels->firstItem() + $index }}</td><td>{{ $mapel->kode_mapel }}</td><td>{{ $mapel->nama_mapel }}</td><td>{{ $mapel->kkm }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('mapel.edit', $mapel) }}" class="btn btn-sm btn-warning" aria-label="Edit mata pelajaran"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" aria-label="Hapus mata pelajaran"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data mata pelajaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $mapels->links() }}
            </div>
        </div>
    </div>
@endsection
