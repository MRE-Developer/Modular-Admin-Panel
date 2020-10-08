<?php


namespace TwoFactorAuth\Http\Responses;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FakeResponses {

    public function dataNotValid($data) {
        //
    }

    public function youShouldBeGuest() {
        //
    }

    public function tokenSent($data, $token) {
        //
    }

    public function tokenIsExpired() {
        //
    }

    public function userRegistered($user) {
        //
    }

    public function passwordIsNotCurrent() {
        //
    }

    public function loginSuccessFully($user) {
        //
    }

    public function userDoesNotExist() {
        //
    }

    public function passwordChanged($mobile) {
        //
    }

    public function profile($user) {
        //
    }

    public function passwordUpdate($user) {
        //
    }
}
