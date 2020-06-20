<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananDistribusi extends Model
{
    protected $table = 'penyimpanan_distribusi';

    protected $fillable = [
        'id',
        'id_user',
        'status',
        'transaksi'
    ];

    public function User() {
        return $this->belongsTo('App\User', 'id_user');
    }
    //
}
