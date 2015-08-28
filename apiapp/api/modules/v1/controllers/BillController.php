<?php
namespace api\modules\v1\controllers;

use common\models\Bill;
use api\modules\v1\controllers\MyRestActionController;


class BillController extends MyRestActionController
{   
    public $modelClass = 'common\models\Bill';

}