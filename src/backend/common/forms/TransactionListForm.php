<?php

namespace common\forms;

use common\classes\TimeService;
use common\dto\TransactionSearchDto;
use common\enums\TransactionTypeEnum;
use common\processors\TransactionListProcessor;
use common\vo\TransactionTypeVoFactory;
use Yii;

class TransactionListForm extends Form
{
    public $limit;
    public $offset;
    public $date_interval_from;
    public $date_interval_till;
    public $amount_from;
    public $amount_till;
    public $type;
    public $comment;
    public $category_name;

    public function rules()
    {
        return [
            [['limit', 'offset'], 'integer', 'min' => 0],
            [['date_interval_from', 'date_interval_till'], 'prepareDates'],
            [['amount_from', 'amount_till'], 'number', 'min' => 0],
            ['type', 'in', 'range' => array_keys(TransactionTypeEnum::$apiTypeToInternalMap)],
            ['type', 'prepareType'],
            [['comment', 'category_name'], 'safe']
        ];
    }

    public function prepareDates()
    {
        if ($this->hasErrors()) {
            return;
        }
        if ($this->date_interval_from !== null) {
            $this->date_interval_from = TimeService::getTimeAsAtom($this->date_interval_from);
        }
        if ($this->date_interval_till !== null) {
            $this->date_interval_till = TimeService::getTimeAsAtom($this->date_interval_till);
        }
    }

    public function prepareType()
    {
        if (!$this->hasErrors() && $this->type !== null) {
            $this->type = TransactionTypeVoFactory::buildByApiName($this->type);
        }
    }

    /**
     * @return \common\dto\TransactionDto[]
     */
    public function process()
    {
        $searchDto = TransactionSearchDto::create()
            ->setUserId($this->getUser()->getId())
            ->setLimit($this->limit)
            ->setOffset($this->offset)
            ->setDateIntervalFrom($this->date_interval_from)
            ->setDateIntervalTill($this->date_interval_till)
            ->setAmountFrom($this->amount_from)
            ->setAmountTill($this->amount_till)
            ->setType($this->type ? : null)
            ->setComment($this->comment)
            ->setCategoryName($this->category_name);

        $resultTransactionDtoArray = TransactionListProcessor::create()
            ->setSearchDto($searchDto)
            ->process();

        return $resultTransactionDtoArray;
    }
}