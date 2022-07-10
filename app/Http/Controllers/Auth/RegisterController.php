<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{

    protected $status = 200;

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'min:3', 'max:255', 'string'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4'],
            'phone' => ['required', 'string', 'regex:/^0\d{9,11}$/'],
        ]);
    }

    protected function create(array $data): User
    {
        $level = env('AUTHORIZATION_LEVEL', 1);
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Register a user",
     * operationId="authRegister",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password", "username" , "department_id", "phone"},
     *       @OA\Property(property="department_id", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="username", type="string", example="user1"),
     *       @OA\Property(property="phone", type="string", example="08122223334"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\Schema(ref="#/components/schemas/User"),
     *     @OA\JsonContent(ref="#/components/schemas/User"),
     *
     *  ),
     *
     * @OA\Response(
     *    response=422,
     *    description="validation error response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The email is required")
     *        ),
     *     )
     *
     * )
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->only([
            'email', 'name', 'password', 'username', 'phone'
        ]))));

        $token = $user->createToken('auth')->plainTextToken;

        Auth::login($user);

        $userResource = (new BaseResource($user))->additional(['token' => $token]);
        return $userResource;
        // $token = $user->createToken('authToken')->plainTextToken;

        // if ($response = $this->registered($request, $user)) {
        // return $response;
        // }
        // return new Response(['token_type' => 'bearer', 'token' => $token], 201);

    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
