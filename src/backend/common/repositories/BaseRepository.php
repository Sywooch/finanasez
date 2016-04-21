<?php

namespace common\repositories;

abstract class BaseRepository
{
    /**
     * @param $id
     * @return \yii\db\ActiveRecord|null
     */
    abstract public function get($id);

    /**
     * @param $id
     * @return bool|false|int
     * @throws \Exception
     */
    public function delete($id)
    {
        $essence = $this->get($id);
        if ($essence) {
            return $essence->delete();
        }

        return false;
    }

    public static function create()
    {
        return new static();
    }
}