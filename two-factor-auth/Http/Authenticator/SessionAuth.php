<?php


namespace TwoFactorAuth\Http\Authenticator;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionAuth {

    public function check() {
        return Auth::check();
    }

    public function user() {
        return Auth::user();
    }

    public function verifyPassword($user, $password) {
        return Hash::check($password, $user->password);
    }

    public function loginUserById($id) {
        auth()->loginUsingId($id);
    }


}
