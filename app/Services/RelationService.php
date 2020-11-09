<?php


namespace App\Services;

use App\Models\IMFriend;
use Illuminate\Support\Facades\Request;

class RelationService
{

    private static $relation;


    private function __construct()
    {

    }


    public function getInstance()
    {
        if (self::$relation instanceof self) {
            return self::$relation;
        }
        self::$relation = new self();
    }


    public function refuseFriends()
    {

    }

    public function addFriends($masterId,$slaveDd)
    {
        $friendModel = new IMFriend();

    }


    public function delFriends()
    {

    }

    public function refuseGroup()
    {

    }

    public function joinGroup()
    {

    }


    public function quitGroup()
    {

    }


    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

}
