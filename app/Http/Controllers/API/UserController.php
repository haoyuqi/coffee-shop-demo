<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser()
    {
        return auth()->guard('api')->user();
    }

    public function putUpdateUser(EditUserRequest $request)
    {
        $user = auth()->user();

        $favoriteCoffee = $request->input('favorite_coffee');
        $flavorNotes = $request->input('flavor_notes');
        $profileVisibility = $request->input('profile_visibility');
        $city = $request->input('city');
        $state = $request->input('state');

        if ($favoriteCoffee) {
            $user->favorite_coffee = $favoriteCoffee;
        }

        if ($flavorNotes) {
            $user->flavor_notes = $flavorNotes;
        }

        if ($profileVisibility) {
            $user->profile_visibility = $profileVisibility;
        }

        if ($city) {
            $user->city = $city;
        }

        if ($state) {
            $user->state = $state;
        }

        $user->save();

        return response()->json(['user_updated' => true], 200);
    }
}
