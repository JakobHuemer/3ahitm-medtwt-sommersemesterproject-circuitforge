<?php

namespace App\Http\Controllers;

use App\Enums\OAuthProviderType;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SUser;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller {

    public function githubRedirect() {
        return Socialite::driver("github")->redirect();
    }

    public function githubAuth() {

        $githubUser = Socialite::driver("github")->user();

        $this->loginWithProvider(OAuthProviderType::GITHUB, $githubUser);

    }


    private function loginWithProvider(OAuthProviderType $provider, SUser $oauth_user) {

        // check if user already exists
        $platformUser = User::where("email", $oauth_user->getEmail())->first();

        if (!$platformUser) {

            $platformUser = new User([
                "email" => $oauth_user->getEmail(),
                "name" => $oauth_user->getNickname(),
            ]);

            $platformUser->save();

        }

        $oauth_provider = new OAuthProvider([
            "user_id" => $platformUser->id,
            "token" => $oauth_user->token,
            "refresh_token" => $oauth_user->refreshToken,
            "provider" => $provider,
        ]);

        $oauth_provider->save();
        $platformUser->markEmailAsVerified();

        Auth::login($platformUser);

    }

}
