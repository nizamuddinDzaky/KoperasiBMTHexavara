<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $table = 'deposito';

    protected $fillable = [
        'id',
        'id_deposito',
        'id_user',
        'id_rekening',
        'jenis_deposito',
        'detail',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }
    public function user(){
        return $this->belongsTo('App\User','id');
    }

}
