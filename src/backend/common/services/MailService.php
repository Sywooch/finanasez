<?php

namespace common\services;

use common\dto\SendMailDto;
use common\dto\UserDto;
use Yii;

class MailService extends BaseService
{
    public function sendRegistration(UserDto $userDto)
    {
        $sendMailDto = SendMailDto::create()
            ->setEmailFrom(Yii::$app->params['supportEmail'])
            ->setEmailTo($userDto->getEmail())
            ->setSubject("Finansez.com | Благодарим вас за регистрацию")
            ->setLayoutFile('@common/views/register_mail')
            ->setLayoutParams(['userDto' => $userDto]);

        $status = $this->send($sendMailDto);

        return $status;
   }

    public function send(SendMailDto $dto)
    {
        Yii::$app->mailer->htmlLayout = false;

        $status = Yii::$app->mailer->compose($dto->getLayoutFile(), $dto->getLayoutParams())
            ->setFrom($dto->getEmailFrom())
            ->setTo($dto->getEmailTo())
            ->setSubject($dto->getSubject())
            ->send();

        return $status;
    }
}