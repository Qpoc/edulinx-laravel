<?php

namespace App\Http\Controllers;

use App\Team;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Request\Auth\RegisterRequest;
use Mpociot\Teamwork\Facades\Teamwork;

class AuthController extends Controller
{

    use ApiResponseHelpers;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return $this->respondNotFound('Account not found. Please check your login details.');
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {

            $user = User::create($request->all());
            $user->assignRole(['Instructor','Student']);
            $user->update([
                'current_role_id' => Role::where('name', 'Student')->first()->id
            ]);
            DB::commit();
            return $this->respondWithSuccess([
                'data' => $user,
                'title' => 'Welcome aboard! ' . $user->first_name,
                'message' => 'Your account has been successfully created. Start exploring!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create user. Please try again later.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // $team = new Team();
        // $team->owner_id = User::where('email', '=', 'cypatungan@gmail.com')->first()->getKey();
        // $team->name = 'My awesome team';
        // $team->invites();
        // $team->save();


        return response()->json(User::with(['currentRole'])->find(Auth::id()));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => User::with(['currentRole'])->find(auth('api')->user()->id),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 24 * 50,
        ]);
    }
}
