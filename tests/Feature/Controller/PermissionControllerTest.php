<?php

namespace Tests\Feature\Controller;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Request\Permission\StoreRequest;
use App\Request\Permission\UpdateRequest;
use App\Http\Controllers\PermissionController;

class PermissionControllerTest extends TestCase
{

    /** @test */
    public function it_gets_all_permissions()
    {
        $controller = new PermissionController();
        $this->assertIsObject($controller->index());
    }

    /** @test */
    public function it_can_paginate_results()
    {
        // Arrange
        $controller = new PermissionController();

        // Act
        $response = $controller->paginate(new Request([
            'search' => '',
        ]));

        // Assert
        $this->assertIsObject($response);
    }

    /** @test */
    public function it_stores_a_permission()
    {
        // Arrange
        $controller = new PermissionController();

        $permission = Factory::create()->colorName();
        $request = new StoreRequest([
            'name' => $permission,
        ]);

        // Act
        $response = $controller->store($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('permissions', [
            'name' => $permission,
        ]);
    }

    /** @test */
    public function it_can_update_a_permission()
    {
        // Arrange
        $controller = new PermissionController();

        $permission = Factory::create()->colorName();
        $request = new UpdateRequest([
            'id' => 1,
            'name' => $permission,
        ]);

        // Act
        $response = $controller->update($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('permissions', [
            'name' => $permission,
        ]);
    }
}
