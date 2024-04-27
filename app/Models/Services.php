<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'nama',
        'kategori_id',
        'harga',
        'satuan_id',
    ];
}
