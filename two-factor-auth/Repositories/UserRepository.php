<?php


namespace TwoFactorAuth\Repositories;


use App\Models\User;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;

class UserRepository {

    public function register($data){

        return User::create([
           'name' => $data['name'],
           'mobile' => $data['mobile'],
           'user_name' => $data['user_name'],
           'verified' => 1,
           'api_token' => Str::random(26),
           'image_profile' => "https://cdn.iconscout.com/icon/free/png-512/avatar-372-456324.png",
           'password' => bcrypt($data['password']),
        ]);
    }

    public function getUserByMobile($mobile){
        return nullable(User::whereMobile($mobile)->first());
    }

    public function updateApiToken($user){
        $user->update([
           "api_token" => Str::random(26)
        ]);
    }

    public function changePassword($user , $password){
        $user->update([
            "password" => bcrypt($password),
        ]);
    }

    public function updatePassword($user, $password){
        $user->update([
            "password" => bcrypt($password),
        ]);
    }

    public function updateProfile($user, $data){
        $user->update($data);
    }
}
