@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Detail Dokumen</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Label: {{ $dokumen->label }}</h5>
                <p class="card-text">Konten:</p>
                <p class="card-text">{{ $dokumen->konten }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
