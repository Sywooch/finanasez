<?php

namespace common\dto;


use common\vo\BaseTransactionTypeVo;

class TransactionSearchDto extends BaseDto
{
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $offset;
    /**
     * @var string
     */
    private $dateIntervalFrom;
    /**
     * @var string
     */
    private $dateIntervalTill;
    /**
     * @var float
     */
    private $amountFrom;
    /**
     * @var float
     */
    private $amountTill;
    /**
     * @var \common\vo\BaseTransactionTypeVo
     */
    private $type;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var string
     */
    private $categoryName;

    /**
     * @var string
     */
    private $userId;

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getDateIntervalFrom()
    {
        return $this->dateIntervalFrom;
    }

    /**
     * @return string
     */
    public function getDateIntervalTill()
    {
        return $this->dateIntervalTill;
    }

    /**
     * @return float
     */
    public function getAmountFrom()
    {
        return $this->amountFrom;
    }

    /**
     * @return float
     */
    public function getAmountTill()
    {
        return $this->amountTill;
    }

    /**
     * @return \common\vo\BaseTransactionTypeVo
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param $dateIntervalFrom
     * @return $this
     */
    public function setDateIntervalFrom($dateIntervalFrom)
    {
        $this->dateIntervalFrom = $dateIntervalFrom;
        return $this;
    }

    /**
     * @param $dateIntervalTill
     * @return $this
     */
    public function setDateIntervalTill($dateIntervalTill)
    {
        $this->dateIntervalTill = $dateIntervalTill;
        return $this;
    }

    /**
     * @param $amountFrom
     * @return $this
     */
    public function setAmountFrom($amountFrom)
    {
        $this->amountFrom = $amountFrom;
        return $this;
    }

    /**
     * @param $amountTill
     * @return $this
     */
    public function setAmountTill($amountTill)
    {
        $this->amountTill = $amountTill;
        return $this;
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
     * @param $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @param $categoryName
     * @return $this
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }
}