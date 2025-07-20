<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "description" => ["required", "string", "max:2000"],
            "price" => ["required", "numeric"],
            "category_id" => ["required", "exists:category,id"]
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "The product name is required.",
            "name.string" => "The product name must be a string.",
            "name.max" => "The product name may not be greater than 255 characters.",

            "description.required" => "The product description is required.",
            "description.string" => "The product description must be a string.",
            "description.max" => "The product description may not be greater than 2000 characters.",

            "price.required" => "The price is required.",
            "price.numeric" => "The price must be a number.",

            "category_id.required" => "The category ID is required.",
            "category_id.exists" => "The selected category ID doesn't exist"
        ];
    }
}
