<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JacobFitzp\LaravelTiptapValidation\Facades\TiptapValidation;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContent;

class StorePostRequest extends FormRequest {
    protected TiptapContent $tiptapContent;

    public function __construct() {
        parent::__construct();

        $this->tiptapContent = TiptapValidation::content()
            ->whitelist()
            ->nodes("doc", "paragraph", "text", "heading", "blockquote", "codeBlock")
            ->marks("bold", "italic", "underline", "strike", "code");
    }


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
            "title" => ["required", "min:2", "max:255", "string"],
            "content" => ["required", "json", $this->tiptapContent],
            'images' => ["array"],
            'images.*' => ["required", "image", "max:2048"], // max 2MB
            'assets' => ["array"],
            'assets.*' => [
                "required",
                "file",
                "mimes:zip,tar,tar.gz,tar.xz,schem,schematic,litematica",
                "max:8192" // max 8MB
            ],
        ];
    }
}
