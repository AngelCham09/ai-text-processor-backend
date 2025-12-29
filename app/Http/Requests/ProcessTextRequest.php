<?php

namespace App\Http\Requests;

use App\Enums\TextActionType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProcessTextRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        //strip_tags => Removes any HTML or PHP tags from the string.
        $this->merge([
            'text' => trim(strip_tags($this->text)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:5000'],
            'action' => ['required', 'string', Rule::in(TextActionType::values())],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Text is required',
            'text.max' => 'Text is too long (max 5000 characters)',
            'action.required' => 'Action type is required',
            'action.in' => 'Invalide action type',
        ];

    }

    protected function failedValidation(Validator $validator)
    {
        Log::warning('Validation failed', ['errors' => $validator->errors()->all()]);
        return parent::failedValidation($validator);
    }
}
