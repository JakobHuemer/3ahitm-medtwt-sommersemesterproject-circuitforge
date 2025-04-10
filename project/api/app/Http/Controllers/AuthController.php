<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use const http\Client\Curl\AUTH_ANY;

class AuthController extends Controller {

    public function register(Request $request): void {

        $request->validate([
            "username" => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        $data = $request->all();
        $user = $this->create($data);

        $user->save();

        Auth::login($user, true);

    }


    public function authenticate(Request $request) {

        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only("login", "password");

        $login = $credentials["login"];
        $password = $credentials["password"];


        if (Auth::attempt(["username" => $login, "password" => $password]) ||
            Auth::attempt(["email" => $login, "password" => $password])) {

            $request->session()->regenerate();

//            return "SUCCESS\nSUCCESS!!";
        }

//        return "FAIL";

    }


    public function create(array $data) {
        return new User([
            'username' => $data['username'],
            'email' => $data["email"],
            'password' => Hash::make($data['password'])
        ]);
    }
}
