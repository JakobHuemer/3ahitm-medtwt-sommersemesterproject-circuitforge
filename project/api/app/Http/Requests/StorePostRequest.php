<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest {
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
            "title" => ["required", "min:2", "max:100", "string"],
            "content" => ["required", "json", TIPTAP_CONTENT],
            'images' => ["array"],
            'images.*' => ["required", "image", "max:2048"], // max 2MB
            'assets' => ["array"],
            'assets.*' => [
                "required",
                "file",
                "max:8192" // max 8MiB
            ],
            "versions" => ["required", "array"],
            "versions.*" => ["required", "string"],
        ];
    }
}
