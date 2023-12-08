<?php
namespace App\Helpers;
use Wamania\Snowball\StemmerFactory;

class TextHelper
{
    public static function removeStopwordsAndPunctuation($text, $stopwords)
    {
        // Inisialisasi sebuah string kosong untuk teks hasil
        $filteredText = '';
        $stemmer = StemmerFactory::create('en');
        // Loop melalui setiap karakter dalam teks
        for ($i = 0; $i < mb_strlen($text); $i++) {
            $char = mb_substr($text, $i, 1);

            // Jika karakter adalah huruf, tambahkan ke teks hasil
            if (preg_match('/\p{L}/u', $char)) {
                $filteredText .= $char;
            } else {
                // Jika karakter bukan huruf, tambahkan spasi sebagai pengganti tanda baca
                $filteredText .= ' ';
            }
        }
        // dd($filteredText);
        // Pisahkan teks yang telah dibersihkan menjadi array kata-kata
        $words = explode(' ', $filteredText);

        // Filter kata-kata yang bukan stopwords
        $filteredWords = array_filter($words, function ($word) use ($stopwords, $stemmer) {
            // Hilangkan tanda baca dari kata dan ubah menjadi huruf kecil
            $cleanedWord = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $word));
            // Stem the cleaned word
            $stemmedWord = $stemmer->stem($cleanedWord);

            // Return false if the word is a stopword or empty after removing punctuation
            return !in_array($stemmedWord, $stopwords) && !empty($stemmedWord);
        });
        // dd($filteredWords);
        // Gabungkan kata-kata yang tersisa kembali menjadi teks
        // $filteredText = implode(' ', $filteredWords);

        return $filteredWords;
    }
}
