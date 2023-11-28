<?php

namespace App\Http\Controllers;

use App\Helper\ResponseContent;
use F9Web\ApiResponseHelpers;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class SwitchRoleController extends Controller
{
    use ApiResponseHelpers;
    public function index(Role $role)
    {

        if (Auth::user()->hasRole($role->id)) {
            Auth::user()->update(['current_role_id' => $role->id]);
            return $this->respondWithSuccess(ResponseContent::getResponse('Role switched successfully', 'success', ''));
        }

        return $this->respondForbidden('You are not allowed to switch to this role');
    }
}
