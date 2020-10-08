<?php


namespace TwoFactorAuth\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use TwoFactorAuth\Facades\AuthFacade;
use TwoFactorAuth\Facades\DataStoreFacade;
use TwoFactorAuth\Facades\ResponderFacade;
use TwoFactorAuth\Facades\TokenGeneratorFacade;
use TwoFactorAuth\Facades\TokenSenderFacade;
use TwoFactorAuth\Facades\UserRepoFacade;

class ForgetController extends Controller {

    public function forget() {
        $input = request()->all();
        $this->validateRequest();
        $this->youShouldBeGuest();

        //Generate Token
        $token = TokenGeneratorFacade::generateToken();

        //Store Mobile in cache
        DataStoreFacade::saveData($input, $token);

        //Send Token to User
        TokenSenderFacade::send($input , $token);

        //Send Response to browser
        return ResponderFacade::tokenSent($input, $token);
    }

    public function verifyForget() {
        $input = request()->all();
        $this->validateRequest();

        //  Verify And Exist Token
        $mobile = DataStoreFacade::getDataByToken($input["token"])->getOrSend(function () {
            return ResponderFacade::tokenIsExpired();
        });

        //  Get User By Mobile in DB
        $user = UserRepoFacade::getUserByMobile($mobile)->getOrSend(function (){
            return ResponderFacade::userDoesNotExist();
        });

        //  Change Password
        UserRepoFacade::changePassword($user, $input["password"]);

        //  Send Successfully Response
        return ResponderFacade::passwordChanged($mobile);
    }

    private function validateRequest() {

        if (Str::contains(\request()->url(), 'forgetPassword')) {
            $rules = [
                'mobile' => ['required', 'regex:/^09(1[0-9]|3[1-9]|2[1-9])-?[0-9]{3}-?[0-9]{4}$/', 'exists:users,mobile']
            ];
        } else {
            $rules = [
                'password' => ['required', "min:6"],
                "token" => ['required' , 'digits:6'],
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
