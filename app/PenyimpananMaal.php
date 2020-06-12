<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananMaal extends Model
{
    protected $table = 'penyimpanan_maal';

    protected $fillable = [
        'id',
        'id_donatur',
        'id_maal',
        'status',
        'transaksi',
    ];

    public function bmt() {
        return $this->belongsTo('App\BMT', 'id');
    }

    public function User(){
        return $this->belongsTo('App\User','id_donatur');
    }

    public function Maal(){
        return $this->belongsTo('App\Maal','id_maal');
    }
    //
}
