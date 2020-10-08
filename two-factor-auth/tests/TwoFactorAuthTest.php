<?php

namespace TwoFactorAuthTest;

use Tests\TestCase;
use TwoFactorAuth\Facades\AuthFacade;
use TwoFactorAuth\Facades\DataStoreFacade;
use TwoFactorAuth\Facades\ResponderFacade;
use TwoFactorAuth\Facades\TokenGeneratorFacade;
use TwoFactorAuth\Facades\TokenSenderFacade;
use TwoFactorAuth\Facades\UserRepoFacade;

class TwoFactorAuthTest extends TestCase {

    public function test_register(){

        AuthFacade::shouldReceive('check')->andReturn(false);
        TokenGeneratorFacade::shouldProxyTo('generateToken');
        DataStoreFacade::shouldReceive('saveData');
        TokenSenderFacade::shouldReceive('send');
        ResponderFacade::shouldReceive('tokenSent');


        $respo = $this->get("/api/register?mobile=09185493615&password=123456&name=Mohamad");
    }

    public function test_userDoesNotGuest(){

        AuthFacade::shouldReceive('check')->andReturn(true);
        ResponderFacade::shouldReceive('youShouldBeGuest');

        $respo = $this->get("/api/register?mobile=09185493615&password=123456&name=Mohamad");
    }

    public function test_dataDoesNotValid(){

        AuthFacade::shouldReceive('check')->andReturn(false);

        ResponderFacade::shouldReceive('dataNotValid');

        $respo = $this->get("/api/register");
    }

    public function test_verify(){

        DataStoreFacade::shouldReceive('getDataByToken');
        UserRepoFacade::shouldReceive('register');
        ResponderFacade::shouldReceive('userRegistered');

        $respo = $this->get("/api/register?mobile=09185493615&token=123456");
    }

    public function test_TokenIsExpired_OR_DoesNotCurrent(){

        AuthFacade::shouldReceive('check')->andReturn(false);
        DataStoreFacade::shouldReceive('getDataByToken')->andReturn(nullable(null));
        ResponderFacade::shouldReceive('tokenIsExpired');

        $respo = $this->get("/api/register?mobile=09185493615&token=123456");
    }

}
