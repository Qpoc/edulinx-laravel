<?php

namespace App\Request\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasRole(['Super Admin', 'Admin']) || Auth::user()->id == $this->route('user');
    }

    public function rules(): array
    {
        return [];
    }
}
