<?php

namespace common\services;

use common\dao\TransactionSearchDao;
use common\dto\TransactionSearchDto;
use common\translators\TransactionActiveRecordToDtoTranslator;

class TransactionSearchService extends BaseService
{
    /**
     * @param TransactionSearchDto $searchDto
     * @return \common\dto\TransactionDto[]
     */
    public function search(TransactionSearchDto $searchDto)
    {
        $transactionArs = TransactionSearchDao::create()
            ->search($searchDto);

        $resultTransactionDtoArray = [];

        foreach ($transactionArs as $transactionAr) {
            $resultTransactionDtoArray[] = TransactionActiveRecordToDtoTranslator::translate($transactionAr);
        }

        return $resultTransactionDtoArray;
    }
}