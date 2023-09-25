@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Dokumen</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('dokumen.create') }}" class="btn btn-primary">Tambah Dokumen</a>
        <a href="{{ route('calculate.tfidf') }}" class="btn btn-primary">Hitung TFIDF</a>
        <a href="{{ route('calculate.vector.models') }}" class="btn btn-primary">Hitung Vector Model</a>




        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Label</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dokumen as $doc)
                    <tr>
                        <td>{{ $doc->id }}</td>
                        <td>{{ $doc->label }}</td>
                        <td>
                            <a href="{{ route('dokumen.show', $doc->id) }}" class="btn btn-info">Lihat</a>
                            <a href="{{ route('dokumen.edit', $doc->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">Hapus</button>
                            </form>
                            <a href="{{ route('calculate.cosine.similarity', $doc->id) }}" class="btn btn-success">Hitung Cosine Similarity</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
