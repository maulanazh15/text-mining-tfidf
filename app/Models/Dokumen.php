<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    public $fillable = ['label', 'konten'];

      // Definisikan relasi dengan model TFIDF
      public function tfidfs()
      {
          return $this->hasMany(TFIDF::class, 'dokumen_id', 'id');
      }
}
