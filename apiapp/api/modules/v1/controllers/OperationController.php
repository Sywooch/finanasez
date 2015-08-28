<?php

namespace api\modules\v1\controllers;

use Yii;
use common\models\Operation;
use api\modules\v1\controllers\MyRestActionController;

class OperationController extends MyRestActionController
{
    public $modelClass = 'common\models\Operation';

    /**
     * Route("/operation")
     * Method: GET
     *
     * @param null $id
     * @param null $bill_id
     * @param null $category_id
     * @param null $time_from
     * @param null $time_to
     * @return mixed
     */
    public function actionIndex($id          = null,
                                $bill_id     = null,
                                $category_id = null,
                                $category_type=null, // available: in | out
                                $time_from   = null,
                                $time_to     = null,
                                $sum         = null,  // available: in | out
                                $limit       = null,
                                $offset      = null
                                )
    {
        // if no additional params, or only id specified, MyRestActionController would handle it
//        if(func_num_args() == 0 || !empty($id)) {
//            return parent::actionIndex($id);
//        }


        if(!empty($id)) {
            $model    = $this->findModelById($id);

            return $this->mergeModels($model);
        }

        $additionalParams = Yii::$app->request->getQueryParams();
        $availableConditions = ['bill_id', 'category_id'];
        $whereCondition = [];
        foreach($availableConditions as $condition) {
            if(isset($additionalParams[$condition])) {
                $whereCondition[$condition] = $additionalParams[$condition];
            }
        }

        if($sum) {
            if(!in_array($sum, ['in', 'out'])) {
                $sum = 'out';
            }
            $models = $this->calculateSum($sum, $whereCondition, $time_from, $time_to);
        } else {
            $models = $this->findAllModelsWithRelations($whereCondition, $category_type, $time_from, $time_to, $limit, $offset);
        }

        return $models;
    }

    /**
     * Route("/")
     * method: POST
     *
     * @return void|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = parent::actionCreate();

        if(!is_object($model)) {
            return $model;
        }

        return $this->mergeModels($model);
    }

    public function actionUpdate($id)
    {
        $model = parent::actionUpdate($id);

        if(!is_object($model)) {
            return $model;
        }

        return $this->mergeModels($model);
    }

    /**
     * return all models for $this->modelClass filtered by params
     *
     * @param array $whereCondition
     * @param null $timeFrom
     * @param null $timeTo
     * @return array
     */
    protected  function findAllModels(array $whereCondition = [], $category_type = null, $timeFrom = null, $timeTo = null)
    {
        $class = $this->modelClass;

        $models = $class::find()
            ->where(array_merge(['operation.user_id'=>Yii::$app->user->getId()], $whereCondition));
        if($timeFrom) {
            $models = $models->andWhere(['>=', 'operation.created_at', $timeFrom]);
        }
        if($timeTo) {
            $models = $models->andWhere(['<=', 'operation.created_at', $timeTo]);
        }
        if($category_type && in_array($category_type, ['in', 'out'])) {
            $models = $models
                        ->joinWith('category', true, 'INNER JOIN')
                        ->andWhere(['category.type' => $category_type]);
        }

        return $models;
    }



    /**
     * finds all models filtered by params & joined it with bill and category
     * so result array will contain bill[ all bill record ] and category [ ... ]
     *
     * @param array $whereCondition
     * @param null $timeFrom
     * @param null $timeTo
     * @return array
     */
    private function findAllModelsWithRelations(array $whereCondition = [],
                                                $category_type = null,
                                                $timeFrom = null,
                                                $timeTo = null,
                                                $limit = null,
                                                $offset = null)
    {
        $result = $this->findAllModels($whereCondition, $category_type, $timeFrom, $timeTo);
        if($limit) {
            $result = $result->limit($limit);
        }
        if($offset) {
            $result = $result->offset($offset);
        }
        return $result->with(['category', 'bill'])
                      ->orderBy(['id'=>SORT_DESC])
                      ->asArray()
                      ->all();

    }

    /**
     * calculates sum of operation
     *
     * @param array $whereCondition
     * @param $timeFrom
     * @param $timeTo
     * @return array
     */
    private function calculateSum($sum, array $whereCondition = [], $timeFrom = null,  $timeTo = null)
    {
        $sum = $this->findAllModels($whereCondition, $timeFrom, $timeTo)
            ->joinWith(['category'])
            ->andWhere(['category.type'=>$sum])
            ->sum('amount');
        if(is_null($sum)) {

            return [];
        }

        return $sum;
    }


    /**
     * merge with model relations(to category and to bill)
     *
     * @param $model
     * @return array
     */
    private function mergeModels($model)
    {
        $category = $model->category->toArray();
        $bill     = $model->bill->toArray();

        return array_merge($model->toArray(),
            [
                'category' => $category
            ],
            [
                'bill'=> $bill
            ]
        );
    }

}
