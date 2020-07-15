<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananLaporanKeuangan extends Model
{
    protected $table = 'penyimpanan_laporan_keuangan';

    protected $fillable = [
        'id',
        'id_user',
        'status',
        'transaksi'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }
}
