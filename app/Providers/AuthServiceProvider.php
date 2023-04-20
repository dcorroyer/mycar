<?php

namespace App\Providers;

//use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

//        $frontEndUrl = env('FRONTEND_URL');
//        $this->setFrontEndUrlInResetPasswordEmail($frontEndUrl);
    }

//    protected function setFrontEndUrlInResetPasswordEmail($frontEndUrl = ''): void
//    {
//        // update url in ResetPassword Email to frontend url
//        ResetPassword::createUrlUsing(function ($user, string $token) use ($frontEndUrl) {
//            return $frontEndUrl . '/auth/password/email/reset?token=' . $token;
//        });
//    }
}
