<?php

namespace common\forms;

use Yii;
use yii\base\Model;
use yii\web\UnauthorizedHttpException;

abstract class Form extends Model
{
    /**
     * @return mixed
     */
    abstract public function process();

    /**
     * @return null|\common\models\User
     * @throws UnauthorizedHttpException
     */
    protected function getUser()
    {
        if (null === ($user = Yii::$app->user->identity)) {
            throw new UnauthorizedHttpException("Ошибка безопасности. Повторите запрос.");
        }
        return $user;
    }
}