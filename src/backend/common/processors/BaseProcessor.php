<?php

namespace common\processors;

use Yii;

abstract class BaseProcessor implements ProcessorInterface
{
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
    }

    public function process()
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $result = $this->safeProcess();

            $dbTransaction->commit();
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            throw $e;
        }
        return $result;
    }

    abstract protected function safeProcess();

}