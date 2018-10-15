<?php
/**
 * Created by PhpStorm.
 * User: nikolaygolub
 * Date: 14.10.2018
 * Time: 00:18
 */

namespace App\Services;


use App\Models\User;

/**
 * Class ProfileService
 * @package App\Services
 */
class ProfileService
{

    /**
     * Update user data
     *
     * @param array $attributes - user data fields to update
     * @param int   $userId - Id of the row need to update
     *
     * @return User - updated user object
     */
    public function update(array $attributes, int $userId) : User
    {
        $user = User::find($userId);
        $user->fill($attributes);
        $user->save();
        return $user;
    }

}