<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class FacebookController extends Controller
{
    public function welcome(){
        return view('facebook.welcome');
    }

    public function thankYou(){

        $name = Session::get('name');
        $email = Session::get('email');
        return view('facebook.thankyou', ['name' => $name, 'email' => $email]);

    }
}
