<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientHomeController extends Controller
{
    public function home(){
        return view('frontend.home');
    }
}

