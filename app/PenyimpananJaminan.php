<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananJaminan extends Model
{
    protected $table = 'penyimpanan_jaminan';

    protected $fillable = [
        'id',
        'id_jaminan',
        'id_pengajuan',
        'id_user',
        'transaksi',
    ];

    public function jaminan() {
        return $this->belongsTo('App\Jaminan', 'id');
    }
    public function pengajuan() {
        return $this->belongsTo('App\Pengajuan', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User','id');
    }

}
