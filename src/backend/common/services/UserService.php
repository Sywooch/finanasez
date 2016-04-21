<?php

namespace common\services;

use common\dto\UserDto;
use common\models\User;
use common\repositories\UserRepository;
use common\translators\UserActiveRecordToDtoTranslator;

class UserService extends BaseService
{
    public function spawn(UserDto $userDto)
    {
        $userAr = new User();
        $userAr->generateToken();
        $userAr->generateAuthKey();

        $this->fillModel($userDto, $userAr);

        $userAr->save();

        $resultUserDto = UserActiveRecordToDtoTranslator::translate($userAr);
        $resultUserDto->setPassword($userDto->getPassword());

        return $resultUserDto;
    }

    public function update(UserDto $userDto)
    {
        $userAr = UserRepository::create()->get($userDto->getId());
        $this->fillModel($userDto, $userAr);

        $userAr->update();

        $resultUserDto = UserActiveRecordToDtoTranslator::translate($userAr);
        $resultUserDto->setPassword($userDto->getPassword());

        return $resultUserDto;
    }

    public function delete(UserDto $userDto)
    {
        throw new \LogicException("Deleting users not supported");
    }

    public function view(UserDto $userDto)
    {
        $userAr = UserRepository::create()
            ->get($userDto->getId());

        $resultUserDto = UserActiveRecordToDtoTranslator::translate($userAr);
        $resultUserDto->setPassword($userDto->getPassword());

        return $resultUserDto;
    }

    protected function fillModel(UserDto $userDto, User $userAr)
    {
        if ($userDto->getUsername()) {
            $userAr->username = $userDto->getUsername();
        }
        if ($userDto->getEmail()) {
            $userAr->email = $userDto->getEmail();
        }
        if ($userDto->getPassword()) {
            $userAr->setPassword($userDto->getPassword());
        }
    }
}