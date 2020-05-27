<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = 'vote';

    protected $fillable = [
        'id_vote',
        'flag',
        'id_user',
        'id_rapat'
    ];
    
    public function User() {
        return $this->belongsTo('App\User', 'id_user');
    }

    public function Rapat() {
        return $this->belongsTo('App\Rapat', 'id_rapat');
    }
}
