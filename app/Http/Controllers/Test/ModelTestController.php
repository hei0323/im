<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ModelTestController extends Controller
{
    public function index()
    {
        $collection = Member::find(7)->store;
    }
}
