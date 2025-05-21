<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest {
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
            "title" => ["min:2", "max:100", "string"],
            "content" => ["json", TIPTAP_CONTENT],
            'images' => ["array"],
            'images.*' => ["required", "image", "max:2048"], // max 2MB
            'assets' => ["array"],
            'assets.*' => [
                "required",
                "file",
                "mimes:zip,tar,tar.gz,tar.xz,schem,schematic,litematica,nbt",
                "max:8192" // max 8MiB
            ],
            "versions" => ["array"],
            "versions.*" => ["required", "string"],
        ];
    }
}
