<?php

namespace common\forms;

use common\dto\UserDto;
use common\models\User;
use common\processors\UserUpdateProcessor;
use Yii;

class UserUpdateForm extends Form
{
    public $password;
    public $email;

    public function rules()
    {
        return [
            ['password', 'safe'],
            ['email', 'string', 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'unique', 'targetClass' => User::className(), 'filter' => ['!=', 'id', $this->getUser()->getId()]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'email' => 'Email'
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($this->getUser()->getId() === Yii::$app->params['demo_user_id']) {
            $this->addError('message', 'Нельзя изменять данные у демо-пользователя');

            return false;
        }

        return parent::validate($attributeNames, $clearErrors);
    }


    /**
     * @return \common\dto\UserDto
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function process()
    {
        $userDto = UserDto::create()
            ->setId($this->getUser()->getId())
            ->setPassword($this->password)
            ->setEmail($this->email);

        $resultUserDto = UserUpdateProcessor::create()
            ->setUserDto($userDto)
            ->process();

        return $resultUserDto;
    }
}