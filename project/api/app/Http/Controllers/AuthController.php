<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller {

    public function register(StoreUserRequest $request) {

        $data = $request->validated();
        $user = new User([
            'username' => $data['username'],
            'email' => $data["email"],
            'password' => Hash::make($data['password'])
        ]);

        $remember = $request->boolean("remember");

        $user->save();

        Auth::login($user, $remember);

        return Auth::user();

    }

    public function authenticate(Request $request) {

        $request->validate([
            'login' => 'required',
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        $credentials = $request->only("login", "password");

        $login = $credentials["login"];
        $password = $credentials["password"];

        $remember = $request->boolean("remember");

        if (Auth::attempt(["username" => $login, "password" => $password], $remember) ||
            Auth::attempt(["email" => $login, "password" => $password], $remember)) {

            return Auth::user();
        }

        throw new HttpException(401, 'Unauthenticated');
    }


    public function logout() {
        Auth::logout();
    }


    public function create(array $data) {
        return new User([
            'username' => $data['username'],
            'email' => $data["email"],
            'password' => Hash::make($data['password'])
        ]);
    }

}
