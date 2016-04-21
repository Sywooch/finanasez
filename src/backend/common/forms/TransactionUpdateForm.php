<?php

namespace common\forms;

use common\dto\TransactionDto;
use common\models\Transaction;
use common\processors\TransactionUpdateProcessor;
use common\repositories\TransactionRepository;
use common\vo\TransactionTypeVoFactory;

class TransactionUpdateForm extends TransactionCreateUpdateForm
{
    public $id;

    /** @var Transaction */
    protected $transaction;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['id', 'required'],
            ['id', 'uuid'],
            ['id', 'validateTransactionId']
        ]);
    }

    public function validateTransactionId()
    {
        if (!$this->hasErrors()) {
            $this->transaction = TransactionRepository::create()->getByIdAndUserId($this->id, $this->getUser()->getId());
            if (!$this->transaction) {
                $this->addError('id', 'Транзакция не найдена или принадлежит другому пользователю.');
            }
        }
    }

    public function process()
    {
        $oldTransactionDto = TransactionDto::create()
            ->setId($this->transaction->id)
            ->setType(TransactionTypeVoFactory::buildByCode($this->transaction->type))
            ->setUserId($this->transaction->user_id)
            ->setAmount($this->transaction->amount)
            ->setBillId($this->transaction->bill_id)
            ->setSourceBillId($this->transaction->source_bill_id)
            ->setCategoryId($this->transaction->category_id)
            ->setComment($this->transaction->comment)
            ->setDatetimeLocal($this->transaction->datetime_local);

        $newTransactionDto = TransactionDto::create()
            ->setId($this->transaction->id)
            ->setUserId($this->transaction->user_id)
            ->setType($this->type ?  : TransactionTypeVoFactory::buildByCode($this->transaction->type))
            ->setAmount($this->amount !== null ? $this->amount : $this->transaction->amount)
            ->setBillId($this->bill_id ? : $this->transaction->bill_id)
            ->setSourceBillId($this->source_bill_id ? : $this->transaction->source_bill_id)
            ->setCategoryId($this->category_id ? : $this->transaction->category_id)
            ->setComment($this->comment ? : $this->transaction->comment)
            ->setDatetimeLocal($this->date ? : $this->transaction->datetime_local);

        $resultTransactionDto = TransactionUpdateProcessor::create()
            ->setOldTransactionDto($oldTransactionDto)
            ->setNewTransactionDto($newTransactionDto)
            ->process();

        return $resultTransactionDto;
    }
}