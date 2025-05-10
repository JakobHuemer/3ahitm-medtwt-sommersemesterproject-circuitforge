<?php

namespace App\Http\Requests;

use App\Enums\VersionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VersionSearchRequest extends FormRequest {
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
            "query" => ["required", "alpha_dash"],
            "types" => [ "required", "array" ],
            "types.*" => [ Rule::enum(VersionType::class) ],
            "limit" => "integer"
         ];
    }
}
