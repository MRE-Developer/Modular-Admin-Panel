<?php


namespace TwoFactorAuth\Http\Responses;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Responses {

    public function dataNotValid($data){
        return response()->json([
            "message" => 'your data is not valid',
            "data" => $data
        ], Response::HTTP_BAD_REQUEST);
    }

    public function youShouldBeGuest(){
        return response()->json([
            "message" => "You Are Already Login."
        ],Response::HTTP_BAD_REQUEST);
    }

    public function tokenSent($data, $token){
        return response()->json([
            "message" => "token was sent.",
            "data" => [
                "mobile" => $data['mobile'],
                "token" => $token,
            ]
        ]);
    }

    public function tokenIsExpired(){
        return response()->json([
            "message" => "token Is Expired OR Does Not Valid.",
        ],Response::HTTP_BAD_REQUEST);
    }

    public function userRegistered($user){
        return response()->json([
            "message" => "Register User is Successfully.",
            "data" => $user
        ]);
    }

    public function passwordIsNotCurrent(){
        return response()->json([
            "message" => "password Is Not Current.",
        ],Response::HTTP_BAD_REQUEST);
    }

    public function loginSuccessFully($user){
        return \response()->json([
           "message" => "You Are Login",
            "data" => $user
        ]);
    }

    public function userDoesNotExist(){
        return response()->json([
            "message" => "User Does Not Exist.",
        ],Response::HTTP_BAD_REQUEST);
    }

    public function passwordChanged($mobile){
        return \response()->json([
            "message" => "Password Changed.",
            "data" => $mobile
        ]);

    }

    public function profile($user){
        return \response()->json([
           "message" => "You Profile.",
            "data" => $user
        ]);
    }

    public function passwordUpdate($user){
        return \response()->json([
            "message" => "Password Changed.",
            "data" => $user
        ]);

    }
}
