<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\RoleController;
use App\Request\Role\StoreRequest;
use App\Request\Role\UpdateRequest;
use Faker\Factory;
use Illuminate\Contracts\Pagination\Paginator;
use Spatie\Permission\Models\Role;

class RoleControllerTest extends TestCase
{
    /** @test */
    public function it_returns_all_role()
    {
        $controller = new RoleController();
        $response = $controller->index();

        $this->assertIsObject($response);
    }

    /** @test */
    public function it_paginates_a_role_successfully()
    {

        $request = new Request();
        $controller = new RoleController();

        $response = $controller->paginate($request);

        $this->assertInstanceOf(Paginator::class, $response);
    }

    /** @test */
    public function it_creates_a_role_successfully()
    {
        // Arrange
        $userData = [
            "name" => Factory::create()->userName(),
            "guard_name" => 'api',
            "permissions" => []
        ];

        $request = new StoreRequest($userData);
        $controller = new RoleController();

        // Act
        $response = $controller->store($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_fails_to_create_a_role()
    {
        // Arrange
        $userData = [];

        $request = new StoreRequest($userData);
        $controller = new RoleController();

        // Act
        $response = $controller->store($request);

        // Assert
        $this->assertEquals(500, $response->getStatusCode());
    }

    /** @test */
    public function it_updates_role_with_valid_data()
    {
        // Arrange
        $requestData = [
            'role' => Role::latest()->first()->id,
            'name' => Factory::create()->name()
        ];

        $request = new UpdateRequest($requestData);
        $controller = new RoleController();

        // Act
        $response = $controller->update($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_updates_role_with_invalid_date()
    {
        // Arrange
        $requestData = [
            'role' => Role::latest()->first()->id,
        ];

        $request = new UpdateRequest($requestData);
        $controller = new RoleController();

        // Act
        $response = $controller->update($request);

        // Assert
        $this->assertEquals(500, $response->getStatusCode());
    }
}
