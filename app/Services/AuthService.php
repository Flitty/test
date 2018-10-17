<?php
/**
 * Created by PhpStorm.
 * User: nikolaygolub
 * Date: 14.10.2018
 * Time: 00:00
 */

namespace App\Services;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    const AVATAR_STORAGE_DISK_NAME = 'avatar';

    /**
     * Prepare user data before registration.
     * Crypt password.
     * Store avatar.
     *
     * @param array $userData
     * @param       $avatar
     *
     * @return array
     */
    public function prepareUserData(array $userData, $avatar = null) : array
    {
        if (isset($userData['password']) && $userData['password']) {
            $userData['password'] = bcrypt($userData['password']);
        } else {
            unset($userData['password']);
        }
        if ($avatar instanceof UploadedFile) {
            $avatar = $this->storeAvatar($avatar);
        }
        $userData['avatar'] = $avatar;
        return $userData;
    }


    /**
     * Store file in storage/app/public/avatars (using of @const AuthService::AVATAR_STORAGE_DISK_NAME disk)
     *
     * @param UploadedFile $avatarFile - uploaded image file by user
     *
     * @return string - full src of the image
     */
    public function storeAvatar(UploadedFile $avatarFile) :? string
    {
        $storageDisk = Storage::disk(static::AVATAR_STORAGE_DISK_NAME);
        $name = $storageDisk->putFile('', $avatarFile);
        return $name ? $storageDisk->url($name) : null;
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}