<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananBMT extends Model
{
    protected $table = 'penyimpanan_bmt';

    protected $fillable = [
        'id',
        'id_user',
        'id_bmt',
        'status',
        'transaksi',
    ];

    public function bmt() {
        return $this->belongsTo('App\BMT', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User','id_user');
    }
    //
}
