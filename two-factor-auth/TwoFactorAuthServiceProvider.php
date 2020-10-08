<?php
namespace TwoFactorAuth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TwoFactorAuth\Facades\AuthFacade;
use TwoFactorAuth\Facades\DataStoreFacade;
use TwoFactorAuth\Facades\ResponderFacade;
use TwoFactorAuth\Facades\TokenGeneratorFacade;
use TwoFactorAuth\Facades\TokenSenderFacade;
use TwoFactorAuth\Facades\UserRepoFacade;
use TwoFactorAuth\Http\Authenticator\FakeSessionAuth;
use TwoFactorAuth\Http\Authenticator\SessionAuth;
use TwoFactorAuth\Http\Responses\FakeResponses;
use TwoFactorAuth\Http\Responses\Responses;
use TwoFactorAuth\Repositories\DataStore;
use TwoFactorAuth\Repositories\Fake\FakeDataStore;
use TwoFactorAuth\Repositories\Fake\FakeTokenSender;
use TwoFactorAuth\Repositories\Fake\FakeUserRepository;
use TwoFactorAuth\Repositories\Fake\FakeTokenGenerator;
use TwoFactorAuth\Repositories\TokenGenerator;
use TwoFactorAuth\Repositories\TokenSender;
use TwoFactorAuth\Repositories\UserRepository;

class TwoFactorAuthServiceProvider extends ServiceProvider {
    private $namespace = "TwoFactorAuth\Http\Controllers";

    public function register(){
        if (app()->runningUnitTests()){
            $responder = FakeResponses::class;
            $auth = FakeSessionAuth::class;
            $userRepository = FakeUserRepository::class;
            $tokenGenerator = FakeTokenGenerator::class;
            $tokenSender = FakeTokenSender::class;
            $dataStore = FakeDataStore::class;
        }else{
            $responder = Responses::class;
            $auth = SessionAuth::class;
            $userRepository = UserRepository::class;
            $tokenGenerator = TokenGenerator::class;
            $tokenSender = TokenSender::class;
            $dataStore = DataStore::class;
        }

        ResponderFacade::shouldProxyTo($responder);
        AuthFacade::shouldProxyTo($auth);
        UserRepoFacade::shouldProxyTo($userRepository);
        TokenGeneratorFacade::shouldProxyTo($tokenGenerator);
        TokenSenderFacade::shouldProxyTo($tokenSender);
        DataStoreFacade::shouldProxyTo($dataStore);

        $this->mergeConfigFrom(__DIR__. '/config/two-factor-auth-config.php', 'two-factor-config');
    }

    public function boot(){
        $this->defineRoutes();
    }

    private function defineRoutes() {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__ . './routes.php');

    }
}
