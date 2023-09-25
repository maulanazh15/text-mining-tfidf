<?php

namespace App\Http\Controllers;

use App\Models\TFIDF;
use App\Models\Dokumen;
use StopWords\StopWords;
use App\Helpers\TextHelper;
use App\Models\VectorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
// use StopWords\StopWords;

class TFIDFController extends Controller
{
    public function calculateTFIDF()
    {
        // Ambil semua dokumen dari tabel Dokumen
        $documents = Dokumen::all();
        // $stopwords = new StopWords("id");
        // Hitung TF-IDF untuk setiap kata dalam setiap dokumen
        TFIDF::truncate();
        foreach ($documents as $document) {
            $documentText = strtolower($document->konten);
            $stopwords = Config::get('stopwords');
            // Hilangkan stopwords dari teks dokumen menggunakan helper
            $filteredText = TextHelper::removeStopwordsAndPunctuation($documentText, $stopwords);
            // Hitung frekuensi kata dalam dokumen
            $wordFreq = array_count_values($filteredText);

            // Jumlah kata dalam dokumen
            $totalWords = count($filteredText);

            foreach ($wordFreq as $word => $freq) {
                // Hitung TF (Term Frequency)
                $tf = $freq / $totalWords;

                // Hitung DF (Document Frequency)
                $df = Dokumen::where('konten', 'LIKE', "%$word%")->count();

                // Hitung IDF (Inverse Document Frequency)
                $idf = log($totalWords / ($df + 1));

                // Hitung TF-IDF
                $tfidf = $tf * $idf;

                // Simpan hasil perhitungan TF-IDF ke dalam tabel TFIDF
                TFIDF::updateOrCreate(
                    ['dokumen_id' => $document->id, 'term' => $word],
                    ['tfidf' => $tfidf]
                );
            }
        }

        // // Hitung vektor model untuk setiap dokumen
        // foreach ($documents as $document) {
        //     $tfidfValues = TFIDF::where('dokumen_id', $document->id)->pluck('tfidf')->toArray();
        //     $magnitude = sqrt(array_sum(array_map(function ($value) {
        //         return pow($value, 2);
        //     }, $tfidfValues)));

        //     // Simpan vektor model ke dalam tabel VectorModel
        //     VectorModel::create([
        //         'dokumen_id' => $document->id,
        //         'magnitude' => $magnitude,
        //     ]);
        // }

        return redirect()->route('dokumen.index')->with('success', 'Perhitungan TF-IDF berhasil.');
    }
}
