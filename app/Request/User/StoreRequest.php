<?php

namespace App\Request\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasRole(['Super Admin', 'Admin']) || $this->route('user') == Auth::id();
    }

    public function rules(): array
    {

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:filter|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'roles' => 'required|array'
        ];

        if ($this->isMethod('PUT')) {
            $this->merge(['id' => $this->route('user')]);
            $rules['id'] = 'required|exists:users,id';
            $rules['email'] = 'required|email:filter|max:255';
        }

        return $rules;
    }
}
