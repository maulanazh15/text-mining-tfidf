<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFIDF extends Model
{
    use HasFactory;

    public $fillable = ['term','tfidf','dokumen_id'];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'id');
    }
}
