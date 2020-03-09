<?php

namespace App\Repositories;
use App\User;

class AccountReporsitories {

    /**
     * Get all account with specific type
     * @return Response
    */
    public function getAccount($type)
    {
        $account = User::where('tipe', $type)->get();
        return $account;
    }
}

?>