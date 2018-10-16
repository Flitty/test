<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{

    /**
     * Get user profile data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['message' => 'User profile data', 'user' => Auth::user()]);
    }

    /**
     * Update @class User::class row for authenticated user
     *
     * @param UpdateUserProfileRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdateUserProfileRequest $request) : JsonResponse
    {
        $image = $request->file('avatar_file') ? $request->file('avatar_file') : $request->get('avatar');
        $userDataFromRequest = $request->only(
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
        $userData = app('auth-service')->prepareUserData($userDataFromRequest, $image);
        app('profile-service')->update($userData, Auth::id());
        $token = auth()->refresh();
        return response()->json(['message' => 'User profile data', 'user' => Auth::user(), 'access_token' => $token]);
    }
}
