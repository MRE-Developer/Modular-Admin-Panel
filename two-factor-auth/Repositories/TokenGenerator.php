<?php


namespace TwoFactorAuth\Repositories;


class TokenGenerator {

    public function generateToken(): int {
        return random_int(100000, 1000000 - 1);
    }
}
