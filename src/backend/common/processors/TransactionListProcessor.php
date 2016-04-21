<?php

namespace common\processors;

use common\dto\TransactionSearchDto;
use common\services\TransactionSearchService;
use Yii;

class TransactionListProcessor extends BaseProcessor
{
    /** @var TransactionSearchDto */
    private $searchDto;

    /**
     * @param TransactionSearchDto $searchDto
     * @return $this
     */
    public function setSearchDto(TransactionSearchDto $searchDto)
    {
        $this->searchDto = $searchDto;
        return $this;
    }

    /**
     * @return \common\dto\TransactionDto[]
     */
    protected function safeProcess()
    {
        $resultTransactionDtoArray = TransactionSearchService::create()
            ->search($this->searchDto);

        return $resultTransactionDtoArray;
    }
}