@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cosine Similarity</h2>
    <hr>
    <p>Label yang mungkin untuk dokumen ini adalah {{ $mostFrequentLabel }} (K = {{ $K }})</p>

    <!-- Slider untuk pemilihan nilai K -->
    <div class="form-group">
        <label for="kSlider">Pilih Nilai K:</label>
        <input type="range" class="form-control-range" id="kSlider" name="k" min="1" max="10" value="{{ $K }}" onchange="updateKValue(this.value)">
        <span id="kValue">{{ $K }}</span>
    </div>

    @if (count($nearestNeighbors) > 0)
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
            @foreach ($nearestNeighbors as $key => $cosineSimilarity)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $cosineSimilarity['dokumen']->id }}</td>
                <td>{{ $cosineSimilarity['dokumen']->label }}</td>
                <td>{{ $cosineSimilarity['cosine_similarity'] }}</td>
            </tr>
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

<script>
    // Fungsi untuk mengupdate nilai K saat slider digeser
    function updateKValue(kValue) {
        // Memperbarui nilai pada elemen span
        document.getElementById("kValue").textContent = kValue;
        // Redirect ke route dengan parameter K yang baru
        window.location.href = '/calculate-cosine-similarity/{{ $queryDocumentId }}/' + kValue;
    }
</script>

@endsection
