<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananDeposito extends Model
{
    protected $table = 'penyimpanan_deposito';

    protected $fillable = [
        'id',
        'id_user',
        'id_deposito',
        'status',
        'transaksi',
    ];

    public function deposito() {
        return $this->belongsTo('App\Deposito', 'id');
    }
    public function user(){
        return $this->belongsTo('App\User','id');
    }
    //
}
