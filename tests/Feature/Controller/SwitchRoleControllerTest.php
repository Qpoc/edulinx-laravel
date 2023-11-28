<?php

namespace Tests\Feature\Controller;

use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Request\Role\StoreRequest;
use Spatie\Permission\Models\Role;
use App\Request\Role\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SwitchRoleController;
use Illuminate\Contracts\Pagination\Paginator;

class SwitchRoleControllerTest extends TestCase
{
    /** @test */
    public function testIndexWithUserRole()
    {
        // Arrange
        $controller = new SwitchRoleController();

        $user = User::find(2);

        Auth::shouldReceive('user')->andreturn($user);

        $role = Role::find(4);

        // Act
        $response = $controller->index($role);

        // Assert
        $this->assertEquals($role->id, Auth::user()->current_role_id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIndexWithoutUserRole()
    {
        // Arrange
        $controller = new SwitchRoleController();

        $user = User::find(2);

        Auth::shouldReceive('user')->andreturn($user);

        $role = Role::find(1);

        // Act
        $response = $controller->index($role);

        // Assert
        $this->assertNotEquals($role->id, Auth::user()->current_role_id);
        $this->assertEquals(403, $response->getStatusCode());
    }
}
