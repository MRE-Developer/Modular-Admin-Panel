<?php


namespace TwoFactorAuth\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use TwoFactorAuth\Facades\AuthFacade;
use TwoFactorAuth\Facades\ResponderFacade;
use TwoFactorAuth\Facades\UserRepoFacade;

class ProfileController extends Controller {

    public function profile(){
        $user =  AuthFacade::user();
        UserRepoFacade::updateApiToken($user);
        return ResponderFacade::profile($user);
    }

    public function changePassword(){

        $input = $this->validateRequest();

        $user = AuthFacade::user();

        UserRepoFacade::updatePassword($user, $input["password"]);

        return ResponderFacade::passwordUpdate($user);

    }

    public function updateProfile(){
        $validatedData = $this->validateRequest();

        //  Get User
        $user = AuthFacade::user();

        //  Update
        UserRepoFacade::updateProfile($user ,array_filter($validatedData));

        return ResponderFacade::profile($user);
    }

    private function validateRequest() {

        $rules = [
            'name' => ['nullable' , 'min:3'],
            'image_profile' => ['nullable' , 'min:10'],
        ];

        if (Str::contains(\request()->url(), 'password')) {
            $rules = [
                'password' => ['required' , 'min:6'],
            ];
        }
        $validateData = Validator::make(\request()->all(), $rules);

        if ($validateData->fails()) {
            ResponderFacade::dataNotValid($validateData->errors())->throwResponse();
        }

        return $validateData->validated();
    }
}
