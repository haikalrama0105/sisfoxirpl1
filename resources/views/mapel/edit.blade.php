@extends('layouts.main')

@section('title', 'Edit Data Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Edit Mata Pelajaran</h6></div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form action="{{ route('mapel.update', $mapel) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group"><label for="kode_mapel">Kode Mapel</label><input id="kode_mapel" name="kode_mapel" class="form-control @error('kode_mapel') is-invalid @enderror" value="{{ old('kode_mapel', $mapel->kode_mapel) }}" required></div>
                    <div class="form-group"><label for="nama_mapel">Nama Mapel</label><input id="nama_mapel" name="nama_mapel" class="form-control @error('nama_mapel') is-invalid @enderror" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required></div>
                    <div class="form-group"><label for="kkm">Nilai KKM</label><input id="kkm" name="kkm" type="number" min="0" max="100" class="form-control @error('kkm') is-invalid @enderror" value="{{ old('kkm', $mapel->kkm) }}" required></div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Perbarui</button>
                    <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
