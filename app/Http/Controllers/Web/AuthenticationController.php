<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{
    public function getSocialRedirect($account)
    {
        try {
            return Socialite::with($account)->redirect();
        } catch (\InvalidArgumentException $e) {
            return redirect('/');
        }
    }

    public function getSocialCallback($account)
    {
        $socialUser = Socialite::with($account)->user();

        $user = User::where([
            'provider' => $account,
            'provider_id' => $socialUser->id
        ])->first();

        if ($user == null) {
            $newUser = new User();

            $newUser->name = $socialUser->getName();
            $newUser->email = $socialUser->getEmail() ?? '';
            $newUser->avatar = $socialUser->getAvatar();
            $newUser->password = '';
            $newUser->provider = $account;
            $newUser->provider_id = $socialUser->getId();
            $newUser->save();

            $user = $newUser;
        }

        Auth::login($user);

        return redirect('/');
    }
}
