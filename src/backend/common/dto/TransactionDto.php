<?php

namespace common\dto;

use common\vo\BaseTransactionTypeVo;

/**
 * @static TransactionDto create()
 */
class TransactionDto extends BaseDto
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $user_id;
    /**
     * @var string
     */
    private $category_id;
    /**
     * @var string
     */
    private $bill_id;
    /**
     * @var float
     */
    private $amount;
    /**
     * @var BaseTransactionTypeVo
     */
    private $type;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var string
     */
    private $source_bill_id;
    /**
     * @var string
     */
    private $created_at;
    /**
     * @var string
     */
    private $updated_at;
    /**
     * @var string
     */
    private $datetime_local;


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param $category_id
     * @return $this
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getBillId()
    {
        return $this->bill_id;
    }

    /**
     * @param $bill_id
     * @return $this
     */
    public function setBillId($bill_id)
    {
        $this->bill_id = $bill_id;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return BaseTransactionTypeVo
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param BaseTransactionTypeVo $type
     * @return $this
     */
    public function setType(BaseTransactionTypeVo $type = null)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceBillId()
    {
        return $this->source_bill_id;
    }

    /**
     * @param $source_bill_id
     * @return $this
     */
    public function setSourceBillId($source_bill_id)
    {
        $this->source_bill_id = $source_bill_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatetimeLocal()
    {
        return $this->datetime_local;
    }

    /**
     * @param $datetime_local
     * @return $this
     */
    public function setDatetimeLocal($datetime_local)
    {
        $this->datetime_local = $datetime_local;
        return $this;
    }
}