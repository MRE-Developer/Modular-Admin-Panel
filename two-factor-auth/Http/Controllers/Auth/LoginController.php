<?php


namespace TwoFactorAuth\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use TwoFactorAuth\Facades\AuthFacade;
use TwoFactorAuth\Facades\ResponderFacade;
use TwoFactorAuth\Facades\UserRepoFacade;

class LoginController extends Controller {

    public function login() {
        $input = request()->all();
        $this->validateRequest();

        $user = UserRepoFacade::getUserByMobile($input['mobile'])->getOrSend(function (){
            return ResponderFacade::userDoesNotExist();
        });

        $valid = AuthFacade::verifyPassword($user, $input['password']);
        if (!$valid){
            return ResponderFacade::passwordIsNotCurrent();
        }

//        UserRepoFacade::updateApiToken($user);

        AuthFacade::loginUserById($user->id);

        return ResponderFacade::loginSuccessFully($user);
    }

    private function validateRequest() {
        $val = Validator::make(\request()->all(),
            [
                'password' => ['required' , 'min:6'],
                'mobile' => ['required', 'regex:/^09(1[0-9]|3[1-9]|2[1-9])-?[0-9]{3}-?[0-9]{4}$/' , 'exists:users,mobile']
            ]);

        if ($val->fails()) {
            ResponderFacade::dataNotValid($val->errors())->throwResponse();
        }

    }
}
