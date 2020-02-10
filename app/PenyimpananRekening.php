<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananRekening extends Model
{
    protected $table = 'penyimpanan_rekening';

    protected $fillable = [
        'id',
        'id_rekening',
        'periode',
        'saldo',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }
}
