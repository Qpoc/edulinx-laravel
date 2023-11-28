<?php

namespace App\Request\Team;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasRole(['Instructor']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'invitations' => 'array',
            'invitations.*' => 'email',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Team name is required',
            'name.string' => 'Team name must be a string',
            'invitations.array' => 'Invitations must be an array',
            'invitations.*.email' => 'Invitations must be an emails',
        ];
    }
}
