<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananTabungan extends Model
{
    protected $table = 'penyimpanan_tabungan';

    protected $fillable = [
        'id',
        'id_user',
        'id_tabungan',
        'status',
        'transaksi',
    ];

    public function tabungan() {
        return $this->belongsTo('App\Tabungan', 'id');
    }
    public function user(){
        return $this->belongsTo('App\User','id_user');
    }
    //
}
