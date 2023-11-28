<?php

namespace Tests\Feature\Controller;

use App\Http\Controllers\AuthController;
use App\Request\Auth\RegisterRequest;
use Faker\Factory;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthControllerTest  extends TestCase
{
    /** @test */
    public function it_logins_user()
    {
        $requestData = [
            'email' => 'cypatungan@gmail.com',
            'password' => 'user'
        ];

        $controller = new AuthController();
        $response = $controller->login(new Request($requestData));

        $this->assertEquals('200', $response->getStatusCode());
    }

    /** @test */
    public function it_registers_user()
    {
        $requestData = [
            "first_name" => Factory::create()->firstName(),
            "last_name" => Factory::create()->lastName(),
            "email" => Factory::create()->email(),
            "password" => "12345678",
            "password_confirmation" => "12345678"
        ];

        $controller = new AuthController();
        $response = $controller->register(new RegisterRequest($requestData));

        $this->assertEquals('200', $response->getStatusCode());
    }

    /** @test */
    public function it_shows_the_authenticated_user()
    {

        $controller = new AuthController();
        $response = $controller->me();

        $this->assertEquals('200', $response->getStatusCode());
    }
}
