<?php

namespace App\Request\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasRole(['Super Admin', 'Admin']);
    }

    public function rules(): array
    {
        $this->merge(['id' => $this->route('role')]);
        return [
            'id' => 'required|exists:roles,id',
            'name' => 'required|string',
        ];
    }
}
