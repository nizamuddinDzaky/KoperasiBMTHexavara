<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananPembiayaan extends Model
{
    protected $table = 'penyimpanan_pembiayaan';

    protected $fillable = [
        'id',
        'id_user',
        'id_pembiayaan',
        'status',
        'transaksi',
    ];

    public function pembiayaan() {
        return $this->belongsTo('App\Pembiayaan', 'id');
    }
    public function user(){
        return $this->belongsTo('App\User','id');
    }
    //
}
