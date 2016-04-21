<?php

namespace common\dto;


class SendMailDto extends BaseDto
{
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $body;
    /**
     * @var string
     */
    private $layoutFile;
    /**
     * @var array
     */
    private $layoutParams;
    /**
     * @var string
     */
    private $emailFrom;
    /**
     * @var string
     */
    private $emailTo;


    /**
     * @param $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }


    /**
     * @param $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param $layoutFile
     * @return $this
     */
    public function setLayoutFile($layoutFile)
    {
        $this->layoutFile = $layoutFile;
        return $this;
    }

    /**
     * @param $emailFrom
     * @return $this
     */
    public function setEmailFrom($emailFrom)
    {
        $this->emailFrom = $emailFrom;
        return $this;
    }

    /**
     * @param $emailTo
     * @return $this
     */
    public function setEmailTo($emailTo)
    {
        $this->emailTo = $emailTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getLayoutFile()
    {
        return $this->layoutFile;
    }

    /**
     * @return string
     */
    public function getEmailFrom()
    {
        return $this->emailFrom;
    }

    /**
     * @return string
     */
    public function getEmailTo()
    {
        return $this->emailTo;
    }

    /**
     * @param array $layoutParams
     * @return $this
     */
    public function setLayoutParams($layoutParams)
    {
        $this->layoutParams = $layoutParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getLayoutParams()
    {
        return $this->layoutParams;
    }
}