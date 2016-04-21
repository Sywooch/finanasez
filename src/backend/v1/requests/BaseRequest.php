<?php

namespace v1\requests;

use common\forms\Form;
use Yii;
use yii\web\HttpException;

abstract class BaseRequest implements InterfaceRequest
{
    /** @var Form */
    protected $form;

    /**
     * @param array|null $data
     * @return array
     */
    public function process($data = [])
    {
        try {
            $this->prepare();

            $this->form->setAttributes($data);

            if (!$this->form->validate()) {
                return $this->form;
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $result = $this->safeProcess();

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } catch (HttpException $e) {
            Yii::$app->response->setStatusCode($e->statusCode);
            $result = $e->getName();
        } catch (\Exception $e) {
            Yii::$app->response->setStatusCode(500);

            $result = 'Внутреняя ошибка процессинга';
            if (YII_DEBUG) {
                $result .= "\n" . $e->getMessage();
                $result .= "\n" . $e->getTraceAsString();
            }
            Yii::error($e);
        }
        return $result;
    }

    /**
     * @param Form $form
     */
    protected function setForm(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    abstract protected function safeProcess();

    /**
     * @return mixed
     */
    abstract protected function prepare();
}
