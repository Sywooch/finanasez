<?php

namespace common\forms;

use common\classes\TimeService;
use Yii;

class StatByCategoryForm extends Form
{
    public $date_interval_from;
    public $date_interval_till;

    public function rules()
    {
        return [
            [['date_interval_from', 'date_interval_till'], 'prepareDates', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'date_interval_from' => 'Дата От',
            'date_interval_till' => 'Дата До'
        ];
    }


    public function prepareDates()
    {
        if ($this->hasErrors()) {
            return;
        }
        if ($this->date_interval_from) {
            $this->date_interval_from = TimeService::getTimeAsAtom($this->date_interval_from);
        }
        if ($this->date_interval_till) {
            $this->date_interval_till = TimeService::getTimeAsAtom($this->date_interval_till);
        }
    }

    public function process()
    {
        return $this->executeQuery();
    }

    private function executeQuery()
    {
        $sqlParams = [
            ':user_id'              => $this->getUser()->getId(),
        ];

        $dateCondition = '';
        if ($this->date_interval_from) {
            $dateCondition .= ' AND datetime_local >= :date_interval_from';
            $sqlParams[':date_interval_from'] = $this->date_interval_from;
        }
        if ($this->date_interval_till) {
            $dateCondition .= ' AND datetime_local <= :date_interval_till';
            $sqlParams[':date_interval_till'] = $this->date_interval_till;
        }

        $sql = "
            select
                category.id,
                category.name,
                category.is_income,
                sum(amount) as sum
            from transaction
                 left join category
                 on transaction.category_id = category.id
            where
                transaction.user_id = :user_id
                {$dateCondition}
            group by category.id, category.name, category.is_income
            having sum(amount) > 0
            order by sum(amount) desc
        ";

        $result = Yii::$app->db->createCommand($sql, $sqlParams)->queryAll();

        return $result;
    }
}