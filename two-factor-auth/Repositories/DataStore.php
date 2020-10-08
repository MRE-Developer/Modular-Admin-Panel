<?php


namespace TwoFactorAuth\Repositories;


class DataStore {
    public function saveData($data , $token){
        $ttl = config('two-factor-config.token_ttl');
        cache()->set($token . "2factor_auth", $data, $ttl);
    }

    public function getDataByToken($token){
        $data = cache()->pull($token . '2factor_auth');
        return nullable($data);
    }
}
