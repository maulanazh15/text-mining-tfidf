@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Cosine Similarity</h2>
        <hr>
        {{-- <p>Label yang mungkin untuk dokumen ini adalah {{ $label }}</p> --}}
        @if (count($cosineSimilarities) > 0)
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Dokumen</th>
                        <th>Label</th>
                        <th>Cosine Similarity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cosineSimilarities as $key => $cosineSimilarity)
                        @if ($key == 0)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $cosineSimilarity['dokumen']->id }}</td>
                            <td>{{ $cosineSimilarity['dokumen']->label }} (Kemungkinan label)</td>
                            <td>{{ $cosineSimilarity['cosine_similarity'] }}</td>
                        </tr> 
                        @else
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $cosineSimilarity['dokumen']->id }}</td>
                            <td>{{ $cosineSimilarity['dokumen']->label }}</td>
                            <td>{{ $cosineSimilarity['cosine_similarity'] }}</td>
                        </tr> 
                        @endif
                        
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada hasil cosine similarity yang tersedia.</p>
        @endif

        <div class="mt-3">
            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
