@extends('layouts.main')

@section('title', 'Edit Data Nilai')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Edit Nilai</h6></div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form action="{{ route('nilai.update', $nilai) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="siswa_id">Siswa</label>
                        <select id="siswa_id" name="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                            @foreach ($siswas as $siswa)
                                <option value="{{ $siswa->id }}" @selected(old('siswa_id', $nilai->siswa_id) == $siswa->id)>{{ $siswa->nis }} - {{ $siswa->nama }} ({{ $siswa->kelas->nama_kelas }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mapel_id">Mata Pelajaran</label>
                        <select id="mapel_id" name="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror" required>
                            @foreach ($mapels as $mapel)
                                <option value="{{ $mapel->id }}" @selected(old('mapel_id', $nilai->mapel_id) == $mapel->id)>{{ $mapel->kode_mapel }} - {{ $mapel->nama_mapel }} (KKM {{ $mapel->kkm }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label for="nilai_tugas">Nilai Tugas (0-100)</label><input id="nilai_tugas" name="nilai_tugas" type="number" step="0.01" min="0" max="100" class="form-control @error('nilai_tugas') is-invalid @enderror" value="{{ old('nilai_tugas', $nilai->nilai_tugas) }}" required></div>
                    <div class="form-group"><label for="nilai_uts">Nilai UTS (0-100)</label><input id="nilai_uts" name="nilai_uts" type="number" step="0.01" min="0" max="100" class="form-control @error('nilai_uts') is-invalid @enderror" value="{{ old('nilai_uts', $nilai->nilai_uts) }}" required></div>
                    <div class="form-group"><label for="nilai_uas">Nilai UAS (0-100)</label><input id="nilai_uas" name="nilai_uas" type="number" step="0.01" min="0" max="100" class="form-control @error('nilai_uas') is-invalid @enderror" value="{{ old('nilai_uas', $nilai->nilai_uas) }}" required></div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Perbarui Nilai</button>
                    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
