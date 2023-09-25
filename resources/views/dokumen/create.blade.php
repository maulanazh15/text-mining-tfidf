@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Dokumen</h2>

        <form method="POST" action="{{ route('dokumen.store') }}">
            @csrf
            <div class="form-group">
                <label for="label">Label</label>
                <input type="text" class="form-control" id="label" name="label" required>
            </div>
            <div class="form-group">
                <label for="konten">Konten</label>
                <textarea class="form-control" id="konten" name="konten" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
