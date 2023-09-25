<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VectorModel extends Model
{
    use HasFactory;

    public $fillable = ['dokumen_id', 'magnitude'];
}
