<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use App\Helper\ResponseContent;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Facades\Teamwork;
use App\Request\Permission\StoreRequest;
use Spatie\Permission\Models\Permission;
use App\Request\Permission\UpdateRequest;

class PermissionController extends Controller
{

    use ApiResponseHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Permission::all();
    }

    public function paginate(Request $request)
    {
        return Permission::when(isset($request->search), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        })->paginate(config('pagination.per_page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'api'
            ]);

            return $this->respondWithSuccess(ResponseContent::getResponse(
                $permission,
                'Permission Creation: Successful',
                'The Permission has been created successfully'
            ));
        } catch (\Exception $e) {
            return ResponseContent::getServerError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        try {
            $permission = Permission::find($request->id);
            $permission->update([
                'name' => $request->name
            ]);
            return $this->respondWithSuccess(ResponseContent::getResponse(
                $permission,
                'Permission Update: Successful',
                'The Permission has been updated successfully'
            ));
        } catch (Exception $e) {
            return ResponseContent::getServerError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
