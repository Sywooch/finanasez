<?php

namespace common\forms;

use common\dto\UserDto;
use common\models\User;
use common\processors\UserCreateProcessor;
use common\processors\UserCreateTriggerRunProcessor;

class UserCreateForm extends Form
{
    public $username;
    public $password;
    public $email;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],

            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            [['username', 'email'], 'unique', 'targetClass' => User::className()],
            ['email', 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'email' => 'Email'
        ];
    }

    /**
     * @return \common\dto\UserDto
     * @throws \Exception
     */
    public function process()
    {
        $userDto = UserDto::create()
            ->setUsername($this->username)
            ->setPassword($this->password)
            ->setEmail($this->email);

        $resultUserDto = UserCreateProcessor::create()
            ->setUserDto($userDto)
            ->process();

        UserCreateTriggerRunProcessor::create()
            ->setUserDto($resultUserDto)
            ->process();

        return $resultUserDto;
    }
}