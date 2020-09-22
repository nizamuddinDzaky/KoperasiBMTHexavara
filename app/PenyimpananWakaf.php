<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananWakaf extends Model
{
    protected $table = 'penyimpanan_wakaf';

    protected $fillable = [
        'id',
        'id_donatur',
        'id_wakaf',
        'status',
        'transaksi',
    ];

    public function bmt() {
        return $this->belongsTo('App\BMT', 'id');
    }

    public function User(){
        return $this->belongsTo('App\User','id_donatur');
    }

    public function Wakaf(){
        return $this->belongsTo('App\Wakaf','id_wakaf');
    }
}
