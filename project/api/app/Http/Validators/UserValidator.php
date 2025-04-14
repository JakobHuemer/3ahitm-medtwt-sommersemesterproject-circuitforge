<?php

namespace App\Http\Validators;

class UserValidator {

    public function usernameRules(): array {
        return [
            'string',
            'min:2',
            'max:40',
            'unique:users',
            'alpha_dash'
        ];
    }

    public function usernameMessage(): string {
        return "";
    }

}
