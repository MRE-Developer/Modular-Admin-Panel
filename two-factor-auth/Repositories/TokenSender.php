<?php


namespace TwoFactorAuth\Repositories;

class TokenSender {

    public function send($data , $token){
        //Send SMS To User
        //Notification::send($user, new LoginTokenNotification($token));
    }
}
