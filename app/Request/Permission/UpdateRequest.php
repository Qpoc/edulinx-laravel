<?php

namespace App\Request\Permission;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::user()->hasRole(['Super Admin', 'Admin']);
    }

    public function rules(): array
    {
        $this->merge(['id' => $this->route('permission')]);
        return [
            'name' => 'required|string',
        ];
    }
}
