<?php

namespace App\Http\Controllers;

use App\Enums\OAuthProviderType;
use App\Models\OAuthProvider;
use App\Models\User;
use Error;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class OAuthController extends Controller {


    public function oauthRedirectHandler(OAuthProviderType $providerType) {
        return Socialite::driver($providerType->value)->redirect();
    }

    public function oauthCallbackHandler(OAuthProviderType $providerType): void {

        // TODO: handle auth errors
        try {
            $oauth_user = Socialite::driver($providerType->value)->stateless()->user();
        } catch (InvalidStateException $exception) {
            echo "MY INVALID STATE EXCEPTION WAS THROWN";
        }

        // check if that auth already exists

        $existingOauthProvider = OAuthProvider::where("provider", $providerType->value)
            ->where("email", $oauth_user->getEmail())
            ->first();

        if ($existingOauthProvider) {
            // update auth entry
            $existingOauthProvider->token = $oauth_user->token;
            $existingOauthProvider->refresh_token = $oauth_user->refreshToken;

            $existingOauthProvider->save();

            $platformUser = User::where("id", $existingOauthProvider->user_id)->first();

        } else {

            // check if user with email already exists
            $platformUser = User::where("email", $oauth_user->getEmail())->first();

            if (!$platformUser) {

                // create new user

                $platformUser = new User([
                    "email" => $oauth_user->getEmail(),
                    "name" => $oauth_user->getNickname() ?? $oauth_user->getName(),
                    "avatar" => $oauth_user->getAvatar(),
                ]);

                $platformUser->save();

            }

            // add oauth entry

            $oauthUser = new OAuthProvider([
                "user_id" => $platformUser->id,
                "token" => $oauth_user->token,
                "refresh_token" => $oauth_user->refreshToken,
                "provider" => $providerType,
                "email" => $oauth_user->getEmail(),
            ]);


            $oauthUser->save();

        }
        Auth::login($platformUser);
    }


    public function addOauthRedirectHandler(OAuthProviderType $providerType) {
        return Socialite::driver($providerType->value)->redirect();
    }

}
