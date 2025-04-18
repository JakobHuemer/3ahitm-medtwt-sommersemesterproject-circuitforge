<?php

namespace App\Http\Controllers;

use App\Enums\OAuthProviderType;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OAuthController extends Controller {


    public function oauthRedirectHandler(OAuthProviderType $providerType) {
        return Socialite::driver($providerType->value)
            ->redirect();
    }

    public function oauthCallbackHandler(OAuthProviderType $providerType): void {

        try {
            $oauth_user = Socialite::driver($providerType->value)->stateless()->user();
        } catch (\Exception $exception) {
            // TODO: better error handling
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
        return Socialite::driver($providerType->value)
            ->redirectUrl(config("services." . $providerType->value . ".redirect_add"))
            ->redirect();
    }

    public function addOAuthCallbackHandler(OAuthProviderType $providerType) {
        try {
            $oauthUser = Socialite::driver($providerType->value)
                ->redirectUrl(config("services." . $providerType->value . ".redirect_add"))
                ->user();
        } catch (\Exception $exception) {
            // TODO: do better error handling
            throw $exception;
//            return "something failed";
        }

        // if auth for provider and user_id already exists

        $providerExists = OAuthProvider
            ::where("id", Auth::id())
            ->where("provider", $providerType->value)
            ->first();

        if ($providerExists) {
            // update

            $providerExists->token = $oauthUser->token;
            $providerExists->refresh_token = $oauthUser->refreshToken;
            $providerExists->email = $oauthUser->getEmail();

            $providerExists->save();
        } else {

            // check if another user has that login

            $otherExistingProvider = OAuthProvider
                ::where("email", $oauthUser->getEmail())
                ->where("provider", $providerType->value)
                ->first();

            if ($otherExistingProvider
                && $otherExistingProvider->user_id != Auth::id()) {
                // this provider is already registerd with another user!
                throw new HttpException(409,
                    "This social login is already connected to another account!");
            }

            // create new for user
            
            $provider = new OAuthProvider([
                "token" => $oauthUser->token,
                "refresh_token" => $oauthUser->refreshToken,
                "user_id" => Auth::id(),
                "provider" => $providerType->value,
                "email" => $oauthUser->getEmail(),
            ]);

            $provider->save();
        }

        return "OKKK";
    }

}
