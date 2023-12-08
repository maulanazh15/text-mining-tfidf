@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Daftar Dokumen</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('dokumen.create') }}" class="btn btn-primary">1. Tambah Dokumen</a>
        <a href="{{ route('calculate.tfidf') }}" class="btn btn-primary">2. Hitung TFIDF</a>
        <a href="{{ route('calculate.vector.models') }}" class="btn btn-primary">3. Hitung Vector Model</a>




        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Label</th>
                    <th>Isi Berita</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dokumen as $doc)
                    <tr>
                        <td>{{ $doc->id }}</td>
                        <td>{{ $doc->label }}</td>
                        <td>{{ str_word_count($doc->konten) > 5 ? implode(' ', array_slice(str_word_count($doc->konten, 1), 0, 5)) . '...' : $doc->konten }}</td>
                        <td>
                            <a href="{{ route('dokumen.show', $doc->id) }}" class="btn btn-info">Lihat</a>
                            <a href="{{ route('dokumen.edit', $doc->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">Hapus</button>
                            </form>
                            <a href="{{ route('calculate.cosine.similarity', ['queryDocumentId' => $doc->id, 'K' => 3]) }}" class="btn btn-success">4. Hitung Cosine Similarity dan KNN</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $dokumen->links() }}
    </div>
@endsection
