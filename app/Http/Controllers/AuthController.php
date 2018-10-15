<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
    * Get a JWT via given credentials.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function login(LoginRequest $request)
    {
        if (! $token = auth()->attempt( $request->only(['email', 'password']))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return app('auth-service')->respondWithToken($token);
    }


    /**
     * Register new user action.
     *
     * @param RegisterRequest $request - validated request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        $image = $request->file('avatar_file') ? $request->file('avatar_file') : $request->get('avatar');
        $userData = $request->only(
            [
                'email',
                'name',
                'password',
                'birthday',
                'gender',
                'news_mailing',
                'biography',
                ]
        );
        $user = User::create(app('auth-service')->prepareUserData($userData, $image));
        if (!$token = auth()->tokenById($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        DB::commit();

        return app('auth-service')->respondWithToken($token);
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
        return app('auth-service')->respondWithToken(auth()->refresh());
    }


}
