<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    protected $table = 'rekening';

    protected $fillable = [
        'id',
        'id_rekening',
        'id_induk',
        'nama_rekening',
        'tipe_rekening',
        'katagori_rekening',
        'detail',
    ];
    //
    public function bmt() {
        return $this->hasOne('App\BMT', 'id_rekening');
    }
    public function tabungan() {
        return $this->hasMany('App\Tabungan', 'id_rekening')->where('status', 'active');
    }
    public function deposito() {
        return $this->hasMany('App\Deposito', 'id_rekening');
    }
}
