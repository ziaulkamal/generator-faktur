<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    protected $fillable = [
        'status',
        'tgl_bayar',
        'id_transaksi',
        'no_meter',
        'id_pelanggan',
        'nama',
        'tarif_daya',
        'no_reff',
        'rp_bayar',
        'token',
    ];

    protected $casts = [
        'tgl_bayar' => 'datetime',
    ];
}
