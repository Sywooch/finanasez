<?php

namespace common\repositories;

use common\models\Category;

/**
 * @static CategoryRepository create()
 */
class CategoryRepository extends BaseRepository
{
    /**
     * @param $id
     * @return Category
     */
    public function get($id)
    {
        return Category::findOne($id);
    }

    /**
     * @param $id
     * @param $userId
     * @return null|Category
     */
    public function getByIdAndUserId($id, $userId)
    {
        return Category::findOne([
            'id'        => $id,
            'user_id'   => $userId
        ]);
    }
}