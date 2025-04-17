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

    public function googleRedirect() {
        return Socialite::driver("google")->redirect();
    }

    public function googleAuth() {
        $googleUser = Socialite::driver("google")->stateless()->user();

        $this->loginWithProvider(OAuthProviderType::GOOGLE, $googleUser);
    }


    private function loginWithProvider(OAuthProviderType $provider, SUser $oauth_user) {

        // check if user already exists
        $platformUser = User::where("email", $oauth_user->getEmail())->first();

        if (!$platformUser) {

            $platformUser = new User([
                "email" => $oauth_user->getEmail(),
                "name" => $oauth_user->getNickname() ?? $oauth_user->getName(),
                "avatar" => $oauth_user->getAvatar(),
            ]);

            $platformUser->save();

        }

        $oauth_provider = new OAuthProvider([
            "user_id" => $platformUser->id,
            "token" => $oauth_user->token,
            "refresh_token" => $oauth_user->refreshToken,
            "provider" => $provider,
        ]);

        echo "<pre>";
        echo "NICK: " . $oauth_user->getNickname();
        echo "\n";
        echo "NAME: " . $oauth_user->getName();
        echo "\n";
        echo "AVATAR: " . $oauth_user->getAvatar();
        echo "</pre>";

        $oauth_provider->save();
        $platformUser->markEmailAsVerified();

        Auth::login($platformUser);

    }

}
