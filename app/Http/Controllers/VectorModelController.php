<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\TFIDF;
use App\Models\VectorModel;
use Illuminate\Support\Facades\DB;

class VectorModelController extends Controller
{
    public function calculateVectorModels()
    {
        // Ambil semua dokumen dari tabel Dokumen
        $documents = Dokumen::all();
        VectorModel::truncate();
        foreach ($documents as $document) {
            // Ambil entri TFIDF terkait dengan dokumen ini
            $tfidfEntries = TFIDF::where('dokumen_id', $document->id)->get();

            // Hitung vektor model untuk dokumen ini
            $magnitude = 0;

            foreach ($tfidfEntries as $tfidfEntry) {
                $magnitude += pow($tfidfEntry->tfidf, 2);
            }

            $magnitude = sqrt($magnitude);

            // Simpan vektor model ke dalam tabel VectorModel
            VectorModel::updateOrCreate(
                ['dokumen_id' => $document->id],
                ['magnitude' => $magnitude]
            );
        }

        return redirect()->route('dokumen.index')->with('success', 'Perhitungan vektor model berhasil.');
    }

    public function calculateCosineSimilarityAndKNN($queryDocumentId, $K=3)
    {
        // Ambil dokumen query
        $queryDocument = Dokumen::find($queryDocumentId);

        // Ambil semua dokumen selain dokumen query
        $documents = Dokumen::where('id', '!=', $queryDocumentId)->get();

        // Hitung cosine similarity untuk setiap dokumen
        $cosineSimilarities = [];

        foreach ($documents as $document) {
            // Ambil vektor model dokumen
            $documentVectorModel = VectorModel::where('dokumen_id', $document->id)->first();

            // Hitung dot product antara vektor dokumen dan vektor query
            $dotProduct = 0;
            $tfidfEntries = TFIDF::where('dokumen_id', $document->id)->get();
            foreach ($tfidfEntries as $tfidfEntry) {
                $queryTfidfEntry = TFIDF::where('dokumen_id', $queryDocument->id)
                    ->where('term', $tfidfEntry->term)
                    ->first();
                if ($queryTfidfEntry) {
                    $dotProduct += $tfidfEntry->tfidf * $queryTfidfEntry->tfidf;
                }
            }

            // Hitung magnitude vektor dokumen
            $documentMagnitude = $documentVectorModel->magnitude;

            // Hitung magnitude vektor query
            $queryVectorModel = VectorModel::where('dokumen_id', $queryDocument->id)->first();
            $queryMagnitude = $queryVectorModel->magnitude;

            // Hitung cosine similarity
            if ($queryMagnitude != 0 && $documentMagnitude != 0) {
                $cosineSimilarity = $dotProduct / ($queryMagnitude * $documentMagnitude);
            } else {
                $cosineSimilarity = 0; // Handle pembagian dengan nol
            }

            // Simpan hasil cosine similarity
            $cosineSimilarities[] = [
                'dokumen' => $document,
                'cosine_similarity' => $cosineSimilarity,
            ];
        }


        // Urutkan hasil berdasarkan cosine similarity terbesar ke terkecil
        usort($cosineSimilarities, function ($a, $b) {
            return $b['cosine_similarity'] <=> $a['cosine_similarity'];
        });

        
        // Pilih K tetangga terdekat
        $nearestNeighbors = array_slice($cosineSimilarities, 0, $K);
        // $label = $cosineSimilarities['dokumen']->label;

        // Hitung mayoritas kelas atau label dari tetangga terdekat
        $labelCounts = [];

        foreach ($nearestNeighbors as $neighbor) {
            // Misalnya, Anda memiliki kolom 'kelas' untuk menyimpan label pada tabel Dokumen
            $dokumen_id = $neighbor['dokumen']->id;
            $label = $neighbor['dokumen']->label; // Dapatkan label dari database

            if (!isset($labelCounts[$label])) {
                $labelCounts[$label] = 0;
            }

            $labelCounts[$label]++;
        }

        // Temukan kelas dengan jumlah terbanyak
        $mostFrequentLabel = array_search(max($labelCounts), $labelCounts);

        // Hasil klasifikasi
        return view('perhitungan.cosine-similiar', compact('nearestNeighbors', 'mostFrequentLabel', 'K', 'queryDocumentId'));
    }
}
