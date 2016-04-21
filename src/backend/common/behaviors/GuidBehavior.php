<?php
namespace common\behaviors;

use common\classes\Guid;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class GuidBehavior extends Behavior
{
    public $idAttribute = 'id';

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'updateGuid'
        ];
    }

    public function updateGuid($event)
    {
        if (empty($this->owner->{$this->idAttribute})) {
            $this->owner->{$this->idAttribute} = Guid::random();
        }
    }
}
