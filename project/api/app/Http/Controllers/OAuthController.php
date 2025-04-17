<?php

namespace App\Http\Controllers;

use App\Enums\OAuthProvider;
use App\Models\User;
use Laravel\Socialite\Contracts\User as SUser;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller {

    public function github_redirect() {
        return Socialite::driver("github")->redirect();
    }

    public function github_auth() {

        $githubUser = Socialite::driver("github")->user();

        $this->loginWithProvider(OAuthProvider::GITHUB, $githubUser);

    }


    private function loginWithProvider(OAuthProvider $provider, SUser $user) {

        // check if user already exists
        echo $user->getEmail();
        $existingUser = User::where("email", $user->getEmail())->first();



    }

}
