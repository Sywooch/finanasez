<?php
namespace common\behaviors;

use yii\behaviors\TimestampBehavior;
use DateTime;
use DateTimeZone;

class TimezoneTimestampBehavior extends TimestampBehavior
{
    public function init()
    {
        parent::init();
        $this->value = (new \DateTime('now', new DateTimeZone('Europe/Moscow')))->format(DateTime::ATOM);
    }
}
