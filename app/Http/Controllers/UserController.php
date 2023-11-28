<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use App\Helper\ResponseContent;
use App\Request\User\ShowRequest;
use App\Request\User\StoreRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    use ApiResponseHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    public function paginate(Request $request)
    {
        return User::when(isset($request->search), function ($query) use ($request) {
            $query->where('first_name', 'LIKE', '%' . $request->search . '%')->orWhere('last_name', 'LIKE', '%' . $request->search . '%')->orWhere('email', 'LIKE', '%' . $request->search . '%');
        })->paginate(config('pagination.per_page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->merge([
                'current_role_id' => $request->roles[0]
            ]);
            $user = User::create($request->all());
            $user->syncRoles($request->roles);
            DB::commit();
            return $this->respondWithSuccess(ResponseContent::getResponse(
                $user,
                'User Creation: Successful',
                'The user has been created successfully'
            ));
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseContent::getServerError();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request)
    {
        return User::find($request->user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request)
    {

        DB::beginTransaction();
        try {
            $user = User::find($request->user);

            $user->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'current_role_id' => $request->roles[0]
            ]);

            $user->syncRoles($request->roles);

            DB::commit();
            return $this->respondWithSuccess(ResponseContent::getResponse(
                $user,
                'User Update: Successful',
                'The user has been updated successfully'
            ));
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseContent::getServerError();
        }
    }
}
