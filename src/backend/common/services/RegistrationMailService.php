<?php

namespace common\services;

use common\dto\SendMailDto;
use common\dto\UserDto;
use Yii;

class RegistrationMailService extends BaseService
{
    public function send(UserDto $userDto)
    {
        $sendMailDto = SendMailDto::create()
            ->setEmailFrom(Yii::$app->params['supportEmail'])
            ->setEmailTo($userDto->getEmail())
            ->setSubject("Finansez.com | Благодарим вас за регистрацию")
            ->setLayoutFile('@common/views/register_mail')
            ->setLayoutParams(['userDto' => $userDto]);

        $status = MailService::create()
            ->send($sendMailDto);

        return $status;
   }
}