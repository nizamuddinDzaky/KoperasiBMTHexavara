<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembiayaan extends Model
{
    protected $table = 'pembiayaan';

    protected $fillable = [
        'id',
        'id_pembiayaan',
        'id_user',
        'id_rekening',
        'jenis_pembiayaan',
        'detail',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }

    public function user(){
        return $this->belongsTo('App\User','id');
    }

}
