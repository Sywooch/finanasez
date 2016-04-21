<?php

namespace common\translators;

use common\dto\UserDto;
use common\models\User;

class UserActiveRecordToDtoTranslator
{
    /**
     * @param User $user
     * @return UserDto
     */
    public static function translate(User $user)
    {
        return UserDto::create()
            ->setId($user->id)
            ->setUsername($user->username)
            ->setAuthKey($user->auth_key)
            ->setToken($user->token)
            ->setPasswordHash($user->password_hash)
            ->setEmail($user->email)
            ->setCreatedAt($user->created_at)
            ->setUpdatedAt($user->updated_at);
    }
}