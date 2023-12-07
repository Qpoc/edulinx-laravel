<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Request\User\StoreRequest;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Request\User\ShowRequest;
use Illuminate\Contracts\Pagination\Paginator;

class UserControllerTest extends TestCase
{

    /** @test */
    public function it_creates_a_user_successfully()
    {
        // Arrange
        $userData = [
            "first_name" => Factory::create()->firstName(),
            "last_name" => Factory::create()->firstName(),
            "email" => Factory::create()->email(),
            "password" => "12345678",
            "password_confirmation" => "12345678",
            "roles" => [
                3,
            ]
        ];

        $request = new StoreRequest($userData);
        $controller = new UserController();

        // Act
        $response = $controller->store($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());

        // Check if the user exists in the database
        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    /** @test */
    public function it_paginates_a_user_successfully()
    {

        $request = new Request();
        $controller = new UserController();

        $response = $controller->paginate($request);

        $this->assertInstanceOf(Paginator::class, $response);
    }

    /** @test */
    public function it_returns_all_user()
    {
        $controller = new UserController();
        $response = $controller->index();

        $this->assertIsObject($response);
    }

    /** @test */
    public function it_updates_a_user()
    {
        $requestData = [
            "user" => 12,
            "first_name" => "Jelai Marie",
            "middle_name" => null,
            "last_name" => "Recierdo",
            "email" => "hello@gmail.com",
            "password" => "12345678",
            "password_confirmation" => "12345678",
            "roles" => [
                1
            ]
        ];

        $request = new StoreRequest($requestData);
        $controller = new UserController();
        $response = $controller->update($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */

    public function it_shows_user(){
        $request = new ShowRequest([
            'user' => 1
        ]);

        $controller = new UserController();

        $response = $controller->show($request);

        $this->assertInstanceOf(User::class, $response);
    }
}
