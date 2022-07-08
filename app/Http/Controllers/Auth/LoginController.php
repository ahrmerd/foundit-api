<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ThrottlesLogins;
use Illuminate\Validation\ValidationException;



class LoginController extends Controller
{
    use ThrottlesLogins;

    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="login with username or email",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
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
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="These credentials do not match our records.")
     *        ),
     *     )
     *
     * )
     */
    public function login(Request $request)
    {
        if (Auth::check())
            return $this->sendLoginResponse($request);
        $this->validateLogin($request);
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username($request) => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        //return Auth::attempt($this->credentials($request), $request->filled('remember'));
        return Auth::guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username($request), 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        //  $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return response(Auth::user(), 200);
    }

    protected function authenticated(Request $request, $user)
    {
        return response(Auth::user(), 200);
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username($request) => [trans('auth.failed')],
        ]);
    }

    public function username($request)
    {
        return $request->has('email') ? 'email' : 'username';
    }


    /**
     * @OA\Get(
     * path="/api/logout",
     * summary="logout authenticated user",
     * operationId="authLogout",
     * tags={"auth"},
     *
     *  @OA\Response(
     *     response=200,
     *     description="Successfully logged out",
     *  ),
     *
     * @OA\Response(
     *    response=500,
     *    description="server error",
     *     )
     * )
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        // $this->guard()->logout();

        // if (method_exists($request, 'session')) {
        //     $request->session()->invalidate();
        //     $request->session()->regenerateToken();
        // }

        if (Auth::check()) {
            return response('unable to logout', 500);
        }
        return response('logged out success', 200);
    }

    protected function loggedOut(Request $request)
    {
        //
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
