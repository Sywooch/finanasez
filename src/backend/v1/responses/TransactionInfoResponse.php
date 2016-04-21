<?php

namespace v1\responses;

use common\classes\TimeService;
use common\dto\BillDto;
use common\dto\CategoryDto;
use common\dto\TransactionDto;
use common\enums\TransactionTypeEnum;
use common\processors\BillViewProcessor;
use common\processors\CategoryViewProcessor;
use common\responses\BaseResponse;

class TransactionInfoResponse extends BaseResponse
{
    /**
     * @var \common\dto\TransactionDto
     */
    private $transactionDto;

    /**
     * @param \common\dto\TransactionDto $transactionDto
     * @return $this
     */
    public function setTransactionDto(TransactionDto $transactionDto)
    {
        $this->transactionDto = $transactionDto;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        $billDto = $this->getTransactionBillDto();

        $response = [
            'id'             => $this->transactionDto->getId(),
            'user_id'        => $this->transactionDto->getUserId(),
            'category_id'    => $this->transactionDto->getCategoryId(),
            'bill_id'        => $this->transactionDto->getBillId(),
            'amount'         => $this->transactionDto->getAmount(),
            'type'           => TransactionTypeEnum::getApiNameByCode((string) $this->transactionDto->getType()),
            'comment'        => $this->transactionDto->getComment(),
            'source_bill_id' => $this->transactionDto->getSourceBillId(),
            'date'           => TimeService::prettyDateTimeFormat($this->transactionDto->getDatetimeLocal())
        ];

        $response['bill'] = [
            'id'        => $billDto->getId(),
            'name'      => $billDto->getName(),
            'balance'   => $billDto->getBalance()
        ];

        if ($this->transactionDto->getCategoryId()) {
            $categoryDto = $this->getTransactionCategoryDto();

            $response['category'] = [
                'id'        => $categoryDto->getId(),
                'name'      => $categoryDto->getName(),
                'is_income' => $categoryDto->getIsIncome()
            ];
        }

        if ($this->transactionDto->getSourceBillId()) {
            $sourceBillDto = $this->getTransactionSourceBillDto();

            $response['source_bill'] = [
                'id'        => $sourceBillDto->getId(),
                'name'      => $sourceBillDto->getName(),
                'balance'   => $sourceBillDto->getBalance()
            ];
        }

        return $response;
    }

    /**
     * @return BillDto
     */
    private function getTransactionBillDto()
    {
        $dto = BillDto::create()
            ->setId($this->transactionDto->getBillId());

        return BillViewProcessor::create()
            ->setBillDto($dto)
            ->process();
    }

    /**
     * @return BillDto
     */
    private function getTransactionSourceBillDto()
    {
        $dto = BillDto::create()
            ->setId($this->transactionDto->getSourceBillId());

        return BillViewProcessor::create()
            ->setBillDto($dto)
            ->process();
    }

    /**
     * @return CategoryDto
     */
    private function getTransactionCategoryDto()
    {
        $dto = CategoryDto::create()
            ->setId($this->transactionDto->getCategoryId());

        return CategoryViewProcessor::create()
            ->setCategoryDto($dto)
            ->process();
    }

}