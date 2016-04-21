<?php

namespace common\forms;

use Yii;

class FrontendLoggerForm extends Form
{
    public $message;
    public $url;
    public $line;
    public $user_agent;

    public function rules()
    {
        return [
            [['message', 'url', 'line', 'user_agent'], 'safe'],
        ];
    }

    public function process()
    {
        Yii::error($this->spawnLogMessage());
    }

    private function spawnLogMessage()
    {
        return "
            Frontend error
            Message: {$this->message}
            Line: {$this->line}
            Url: {$this->url}
            User-Agent: {$this->user_agent}
            ";
    }
}