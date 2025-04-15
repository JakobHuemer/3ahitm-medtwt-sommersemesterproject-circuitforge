<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            "username" => ["required", "min:2", "max:40", "unique:users"],
            "email" => ["required", "email", "unique:users"],
            "password" => ['required', "min:8"]
        ];
    }

    public function messages() {
        return [
            "username.min" => "username musst be at least 2 long",
            "username.max" => "username cannot be over 40 long",
            "username.required" => "a username is required",
            "username.unique" => "this username is already used",

            "email.required" => "an email is required",
            "email.unique" => "this email is already used",
            "email.email" => "the email musst be valid",

            "password.required" => "a password is required",
            "password.min" => "password musst be at least 8 characters long"
        ];
    }


}
