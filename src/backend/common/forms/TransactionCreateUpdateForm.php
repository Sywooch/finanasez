<?php

namespace common\forms;

use common\classes\TimeService;
use common\enums\TransactionTypeEnum;
use common\models\Bill;
use common\models\Category;
use common\repositories\BillRepository;
use common\repositories\CategoryRepository;
use common\vo\IncomeTransactionTypeVo;
use common\vo\TransactionTypeVoFactory;
use common\vo\TransferTransactionTypeVo;

abstract class TransactionCreateUpdateForm extends Form
{
    public $amount;
    public $type;
    public $bill_id;
    public $category_id;
    public $source_bill_id;
    public $comment;
    public $date;

    public function rules()
    {
        return [
            ['amount', 'number', 'min' => 1E-06],

            ['type', 'in', 'range' => array_keys(TransactionTypeEnum::$apiTypeToInternalMap)],
            ['type', 'validateType'],

            ['bill_id', 'uuid'],
            ['bill_id', 'validateBillId'],

            ['category_id', 'uuid'],
            ['category_id', 'validateCategoryId'],

            ['source_bill_id', 'uuid'],
            ['source_bill_id', 'compare', 'compareAttribute' => 'bill_id', 'operator' => '!=='],
            ['source_bill_id', 'validateBillId'],
            ['source_bill_id', 'validateSourceBillId', 'skipOnEmpty' => false],

            ['comment', 'safe'],
            ['date', 'setDate'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Сумма',
            'comment' => 'Комментарий',
            'category_id' => 'Категория',
            'source_bill_id' => 'Счет',
            'bill_id' => 'Счет',
            'type' => 'Тип',
            'date' => 'Дата',
        ];
    }


    public function validateType()
    {
        if (!$this->hasErrors()) {
            $this->type = TransactionTypeVoFactory::buildByApiName($this->type);
        }
    }

    public function validateBillId($attribute)
    {
        if (!$this->hasErrors()) {
            $bill = BillRepository::create()->getByIdAndUserId($this->$attribute, $this->getUser()->getId());

            if (!$bill) {
                $this->addError($attribute, "$attribute не найден или принадлежит другому пользователю");
                return;
            }
        }
    }

    public function validateSourceBillId()
    {
        if (!$this->hasErrors()) {
            if ($this->type instanceof TransferTransactionTypeVo &&
                !$this->source_bill_id
            ) {
                $this->addError('source_bill_id', 'Поле ялвяется обязательным');
            }
        }
    }

    public function validateCategoryId()
    {
        if (!$this->hasErrors()) {
            $category = CategoryRepository::create()->getByIdAndUserId($this->category_id, $this->getUser()->getId());

            if (!$category) {
                $this->addError('category_id', 'Категория не найдена или принадлежит другому пользователю');
                return;
            }

            if ($category->is_income && !($this->type instanceof IncomeTransactionTypeVo)) {
                $this->addError('category_id', 'В категорию с типом "доход" можно добавить только доходную операцию');
                return;
            }
        }
    }

    public function setDate()
    {
        if (!$this->hasErrors() && $this->date) {
            $this->date = TimeService::getTimeAsAtom($this->date);
        }
    }
}