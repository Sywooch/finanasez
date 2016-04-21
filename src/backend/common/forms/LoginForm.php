<?php

namespace common\forms;

use common\translators\UserActiveRecordToDtoTranslator;
use Yii;
use common\models\User;

class LoginForm extends Form
{
    public $username;
    public $password;

    /** @var User */
    private $user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string', 'max' => 255],
            ['password', 'validatePassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль'
        ];
    }

    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->findUser();
            if (!$user) {
                $this->addError('username', 'Пользователь не найден');
                return;
            }

            if (!$user->validatePassword($this->password)) {
                $this->addError('password', 'Неверное имя пользователя или пароль');
            }
        }
    }

    protected function findUser()
    {
        if ($this->user === null) {
            $this->user = User::findByUsername($this->username);
        }

        return $this->user;
    }

    /**
     * @return \common\dto\UserDto
     */
    public function process()
    {
        $userAr = $this->findUser();

        Yii::$app->user->login($userAr, 3600 * 24);

        /** @var User $userAr */
        $userAr = Yii::$app->user->identity;

        $resultUserDto = UserActiveRecordToDtoTranslator::translate($userAr);

        return $resultUserDto;
    }
}