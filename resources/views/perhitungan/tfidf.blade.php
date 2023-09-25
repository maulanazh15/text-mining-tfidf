@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tabel TF-IDF</h2>

        @if (count($tfidfs) > 0)
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Dokumen</th>
                        <th>Term</th>
                        <th>TF-IDF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tfidfs as $key => $tfidf)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $tfidf->dokumen_id }}</td>
                            <td>{{ $tfidf->term }}</td>
                            <td>{{ $tfidf->tfidf }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data TF-IDF yang tersedia.</p>
        @endif

        <div class="mt-3">
            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
