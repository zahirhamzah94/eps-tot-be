<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|max:52428800', // 50MB max
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'sometimes|boolean',
            'expires_at' => 'nullable|date|after:now',
            'metadata' => 'nullable|json',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Please provide a file to upload.',
            'file.file' => 'The uploaded content is not a valid file.',
            'file.max' => 'File size cannot exceed 50MB.',
            'expires_at.after' => 'Expiration date must be in the future.',
        ];
    }
}
