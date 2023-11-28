<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use App\Helper\ResponseContent;
use App\Request\Role\StoreRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Request\Role\UpdateRequest;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{

    use ApiResponseHelpers;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Role::all();
    }

    public function paginate(Request $request)
    {
        return Role::when(isset($request->search), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        })->with(['permissions'])->paginate(config('pagination.per_page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name, 'guard_name' => 'api']);
            if (count($request->permissions) > 0) {
                $role->syncPermissions($request->permissions);
            }
            DB::commit();
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            return $this->respondWithSuccess(ResponseContent::getResponse(
                $role,
                'Role Creation: Successful',
                'The role has been created successfully'
            ));
        } catch (Exception $e) {
            DB::rollBack();
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
        DB::beginTransaction();
        try {
            $role = Role::find($request->role);
            $role->name = $request->name;
            $role->save();

            $role->syncPermissions($request->permissions ?? []);

            DB::commit();
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            return $this->respondWithSuccess(ResponseContent::getResponse(
                $role,
                'Role Update: Successful',
                'The role has been updated successfully'
            ));
        } catch (Exception $e) {
            DB::rollBack();
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
