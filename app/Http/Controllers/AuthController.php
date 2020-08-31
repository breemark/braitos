<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function register(UserRegisterRequest $request)
    {


        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|min:6|confirmed',
        // ]);

        // if ($validator->fails()) {
        //     return ['error' => $validator->errors()];
        // }

        $user = User::create([
            'name'    => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //$token = auth()->login($user);

        return response()->json(['message' => 'Successfully created user']);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function update_me(User $user)
    {
        $user = Auth::user();

        if ($user->email == request()->email) {
            return [
                'message' => 'The new email should be different from the old email'
            ];
        }

        $this->validate(request(), [
            'email' => 'email|unique:users',
            'password' => 'min:6|confirmed'
        ]);

        if (request('name')) {
            $user->name = request('name');
        };
        if (request('email')) {
            $user->email = request('email');
        };
        if (request('password')) {
            $user->password = bcrypt(request('password'));
        };

        $user->save();
    }
}
