<?php

namespace common\services;

use common\dto\TransactionDto;
use common\models\Transaction;
use common\repositories\TransactionRepository;
use common\translators\TransactionActiveRecordToDtoTranslator;

class TransactionService extends BaseService
{
    /**
     * @param TransactionDto $transactionDto
     * @return TransactionDto
     */
    public function spawn(TransactionDto $transactionDto)
    {
        $transactionAr = new Transaction();
        $this->fillModel($transactionDto, $transactionAr);

        $transactionAr->save();
        $transactionAr->refresh();

        $resultTransactionDto = TransactionActiveRecordToDtoTranslator::translate($transactionAr);

        return $resultTransactionDto;
    }

    /**
     * @param TransactionDto $transactionDto
     * @return TransactionDto
     */
    public function update(TransactionDto $transactionDto)
    {
        $transactionAr = TransactionRepository::create()
            ->get($transactionDto->getId());
        $this->fillModel($transactionDto, $transactionAr);

        $transactionAr->save();
        $transactionAr->refresh();

        $resultTransactionDto = TransactionActiveRecordToDtoTranslator::translate($transactionAr);

        return $resultTransactionDto;
    }

    /**
     * @param TransactionDto $transactionDto
     */
    public function delete(TransactionDto $transactionDto)
    {
        TransactionRepository::create()
            ->delete($transactionDto->getId());
    }

    /**
     * @param TransactionDto $transactionDto
     * @return TransactionDto
     */
    public function view(TransactionDto $transactionDto)
    {
        $transaction = TransactionRepository::create()
            ->get($transactionDto->getId());

        $resultTransactionDto = TransactionActiveRecordToDtoTranslator::translate($transaction);

        return $resultTransactionDto;
    }

    /**
     * @param TransactionDto $dto
     * @param Transaction $transactionAr
     */

    protected function fillModel(TransactionDto $dto, Transaction $transactionAr)
    {
        if ($dto->getUserId()) {
            $transactionAr->user_id = $dto->getUserId();
        }
        if ($dto->getBillId()) {
            $transactionAr->bill_id = $dto->getBillId();
        }
        if ($dto->getCategoryId()) {
            $transactionAr->category_id = $dto->getCategoryId();
        }
        if ($dto->getAmount()) {
            $transactionAr->amount = $dto->getAmount();
        }
        if ($dto->getComment()) {
            $transactionAr->comment = $dto->getComment();
        }
        if ($dto->getType()) {
            $transactionAr->type = (string) $dto->getType();
        }
        if ($dto->getSourceBillId()) {
            $transactionAr->source_bill_id = $dto->getSourceBillId();
        }
        if ($dto->getDatetimeLocal()) {
            $transactionAr->datetime_local = $dto->getDatetimeLocal();
        }
    }
}