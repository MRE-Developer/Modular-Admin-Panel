<?php


namespace TwoFactorAuth\Repositories\Fake;


use App\User;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;

class FakeUserRepository {

    public function register($data) {
        //
    }

    public function getUserByMobile($mobile) {
        //
    }

    public function updateApiToken($user) {
        //
    }

    public function changePassword($user, $password) {
        //
    }

    public function updatePassword($user, $password) {
        //
    }

    public function updateProfile($user, $data) {
        //
    }
}
