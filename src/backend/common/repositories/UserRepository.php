<?php

namespace common\repositories;

use common\models\User;
use common\models\Bill;
use common\models\Category;

/**
 * @static UserRepository create()
 */
class UserRepository extends BaseRepository
{
    /**
     * @param $id
     * @return User
     */
    public function get($id)
    {
        return User::findOne($id);
    }

    public function delete($id)
    {
        throw new \LogicException("Deleting users not supported");
    }

    /**
     * @param $id
     * @return Bill[]
     */
    public function getBills($id)
    {
        $user = $this->get($id);
        if ($user) {
            return $user->bills;
        } else {
            return [];
        }
    }

    /**
     * @param $id
     * @return Category[]
     */
    public function getCategories($id)
    {
        $user = $this->get($id);
        if ($user) {
            return $user->categories;
        } else {
            return [];
        }
    }
}