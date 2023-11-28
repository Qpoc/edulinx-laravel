<?php

namespace App\Request\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasRole(['Super Admin', 'Admin']);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles'
        ];
    }
}
