<?php

namespace App\Request\Team;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && User::find(Auth::id())->teams(function ($query) {
            $query->where([
                ['team_id', $this->route('team_id')],
                ['user_id', Auth::id()]
            ]);
        })->first();
    }

    public function rules(): array
    {
        return [
            'team_id' => 'required|exists:teams,id',
        ];
    }

    public function prepareForValidation(){
        $this->merge([
            'team_id' => $this->route('team_id')
        ]);
    }
}
