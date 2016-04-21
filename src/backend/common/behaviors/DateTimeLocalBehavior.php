<?php
namespace common\behaviors;

use common\classes\TimeService;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class DateTimeLocalBehavior extends Behavior
{
    public $dateTimeLocalAttribute = 'datetime_local';

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'updateDatetimeLocal',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updateDatetimeLocal'
        ];
    }

    public function updateDatetimeLocal()
    {
        if (empty($this->owner->{$this->dateTimeLocalAttribute})) {
            $this->owner->{$this->dateTimeLocalAttribute} = TimeService::create()->asAtom();
        }
    }

}
