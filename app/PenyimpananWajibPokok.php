<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananWajibPokok extends Model
{
    protected $table = 'penyimpanan_wajib_pokok';

    protected $fillable = [
        'id',
        'id_user',
        'id_rekening',
        'status',
        'transaksi',
    ];

    public function tabungan() {
        return $this->belongsTo('App\Rekening', 'id');
    }
    public function user(){
        return $this->belongsTo('App\User','id_user');
    }
    //
}
