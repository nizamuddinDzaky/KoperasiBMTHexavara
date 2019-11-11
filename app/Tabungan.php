<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    protected $table = 'tabungan';

    protected $fillable = [
        'id',
        'id_tabungan',
        'id_user',
        'id_rekening',
        'jenis_tabungan',
        'detail',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }

    public function user(){
        return $this->belongsTo('App\User','id');
    }

}
