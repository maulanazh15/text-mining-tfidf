@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tabel Vector Model</h2>

        @if (count($vectorModels) > 0)
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Dokumen</th>
                        <th>Magnitude</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vectorModels as $key => $vectorModel)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $vectorModel->dokumen_id }}</td>
                            <td>{{ $vectorModel->magnitude }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data Vector Model yang tersedia.</p>
        @endif

        <div class="mt-3">
            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
