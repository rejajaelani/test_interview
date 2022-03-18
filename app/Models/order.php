<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id_pembelian',
        'nama_pelanggan',
        'tanggal',
        'jam',
        'total',
        'bayar_tunai',
        'kembali',
    ];
}
