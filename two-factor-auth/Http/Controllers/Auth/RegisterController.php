<?php

namespace TwoFactorAuth\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use TwoFactorAuth\Facades\AuthFacade;
use TwoFactorAuth\Facades\DataStoreFacade;
use TwoFactorAuth\Facades\ResponderFacade;
use TwoFactorAuth\Facades\TokenGeneratorFacade;
use TwoFactorAuth\Facades\TokenSenderFacade;
use TwoFactorAuth\Facades\UserRepoFacade;

class RegisterController extends Controller {

    public function register() {
        $input = \request()->all();

        $this->youShouldBeGuest();
        $this->validateRequest();

        $token = TokenGeneratorFacade::generateToken();

        //Store Data in cache
        DataStoreFacade::saveData($input, $token);

        TokenSenderFacade::send($input, $token);

        return ResponderFacade::tokenSent($input, $token);
    }

    public function verify() {
        $input = \request()->all();

        $this->youShouldBeGuest();
        $this->validateRequest();

        // Verify Token
        $UserData = DataStoreFacade::getDataByToken($input['token'])->getOrSend(function () {
            return ResponderFacade::tokenIsExpired();
        });

        $user = UserRepoFacade::register($UserData);

        return ResponderFacade::userRegistered($user);
    }

    private function validateRequest() {

        if (Str::contains(\request()->url(), 'register')) {
            $rules = [
                'name' => ['required', 'min:3'],
                'password' => ['required', 'min:6'],
                "user_name" => ['required', 'min:6', 'unique:users,user_name'],
                'mobile' => ['required', 'regex:/^09(1[0-9]|3[1-9]|2[1-9])-?[0-9]{3}-?[0-9]{4}$/', 'unique:users,mobile']
            ];
        } else {
            $rules = [
                "token" => ['required', 'min:6'],
                'mobile' => ['required', 'regex:/^09(1[0-9]|3[1-9]|2[1-9])-?[0-9]{3}-?[0-9]{4}$/', 'unique:users,mobile']
            ];
        }

        $val = Validator::make(\request()->all(), $rules);

        if ($val->fails()) {
            ResponderFacade::dataNotValid($val->errors())->throwResponse();
        }
    }

    public function youShouldBeGuest() {
        if (AuthFacade::check()) {
            ResponderFacade::youShouldBeGuest()->throwResponse();
        }
    }
}
