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
        $userDataFromRequest = [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'password' => $request->get('password'),
            'birthday' => $request->get('birthday'),
            'gender' => $request->get('gender'),
            'news_mailing' => $request->get('news_mailing'),
            'biography' => $request->get('biography'),
        ];
        $userData = app('auth-service')->prepareUserData($userDataFromRequest, $image);
        $user = app('profile-service')->update($userData, Auth::id());
        $token = auth()->refresh();
        return response()->json(['message' => 'User profile data has been updated', 'user' => $user, 'access_token' => $token]);
    }
}
