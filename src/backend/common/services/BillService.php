<?php

namespace common\services;

use common\dto\BillDto;
use common\models\Bill;
use common\repositories\BillRepository;
use common\repositories\UserRepository;
use common\translators\BillActiveRecordToDtoTranslator;

class BillService extends BaseService
{
    /**
     * @param BillDto $billDto
     * @return BillDto
     */
    public function spawn(BillDto $billDto)
    {
        $billAr = new Bill();
        $this->fillModel($billDto, $billAr);

        $billAr->save();
        $billAr->refresh();

        $resultBillDto = BillActiveRecordToDtoTranslator::translate($billAr);

        return $resultBillDto;
    }

    /**
     * @param BillDto $billDto
     * @return BillDto
     */
    public function update(BillDto $billDto)
    {
        $billAr = BillRepository::create()
            ->get($billDto->getId());
        $this->fillModel($billDto, $billAr);

        $billAr->save();
        $billAr->refresh();

        $resultBillDto = BillActiveRecordToDtoTranslator::translate($billAr);

        return $resultBillDto;
    }

    /**
     * @param BillDto $billDto
     */
    public function delete(BillDto $billDto)
    {
        BillRepository::create()
            ->delete($billDto->getId());
    }

    /**
     * @param BillDto $billDto
     * @return BillDto
     */
    public function view(BillDto $billDto)
    {
        $billAr =  BillRepository::create()
            ->get($billDto->getId());

        $resultBillDto = BillActiveRecordToDtoTranslator::translate($billAr);

        return $resultBillDto;
    }

    /**
     * @param BillDto $billDto
     * @return BillDto[]
     */
    public function getForUser(BillDto $billDto)
    {
        $billArs =  UserRepository::create()
            ->getBills($billDto->getUserId());

        $resultBillDtoArray = [];

        foreach ($billArs as $billAr) {
            $resultBillDtoArray[] = BillActiveRecordToDtoTranslator::translate($billAr);
        }

        return $resultBillDtoArray;
    }

    /**
     * @param BillDto $billDto
     * @param Bill $billAr
     */
    protected function fillModel(BillDto $billDto, Bill $billAr)
    {
        $billAr->user_id = $billDto->getUserId();

        if ($billDto->getName()) {
            $billAr->name = $billDto->getName();
        }
        if ($billDto->getBalance() !== null) {
            $billAr->balance = $billDto->getBalance();
        }
    }
}