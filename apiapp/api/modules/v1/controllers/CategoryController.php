<?php

namespace api\modules\v1\controllers;

use common\models\Category;
use api\modules\v1\controllers\MyRestActionController;

class CategoryController extends MyRestActionController
{
    public $modelClass = 'common\models\Category';

}
