<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = [
        'name',
        'kategori',
        'satuan',
        'brand_barang',
        'stok',
        'harga_eceran',
        'harga_reseller',
        'deskripsi'
    ];
}