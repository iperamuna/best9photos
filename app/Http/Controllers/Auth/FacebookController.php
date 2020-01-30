<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\PickBest9FacebookPhotosFromLastYear;
use App\User;
use Facebook\Facebook;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('facebook')->user();

        $user = User::updateOrCreate([
            'email' => $user->getEmail()
        ], [
            'name' => $user->getName(),
            'facebook_token' => $user->token
        ]);

        PickBest9FacebookPhotosFromLastYear::dispatch($user);

        return redirect('thankyou')->with(['name' => $user->name , 'email' => $user->email]);
    }
}
