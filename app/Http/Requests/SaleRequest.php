<?php

namespace App\Http\Requests;


class SaleRequest extends ApiFormRequest
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
            'sale_date' => 'required|date',
            'email' => 'required|email',
            'concepts' => 'required|array|min:1',
            'concepts.*.quantity' => 'required|numeric',
            'concepts.*.product_id' => 'required|exists:product,id'
        ];
    }

    public function messages()
    {
        return [
            'sale_date.required' => 'The sale date is required.',
            'sale_date.date' => 'The sale date must be a valid date.',

            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid format.',

            'concepts.required' => 'At least one concept is required.',
            'concepts.array' => 'The concepts field must be an array.',
            'concepts.min' => 'You must provide at least one concept.',

            'concepts.*.quantity.required' => 'The quantity for each concept is required.',
            'concepts.*.quantity.numeric' => 'The quantity must be a numeric value.',

            'concepts.*.product_id.required' => 'The product is required for each concept.',
            'concepts.*.product_id.exists' => 'The selected product does not exist.',
        ];
    }
}
