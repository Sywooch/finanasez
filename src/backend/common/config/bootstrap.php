<?php

Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('v1', dirname(dirname(__DIR__)) . '/v1');

\yii\validators\Validator::$builtInValidators['uuid'] = \common\validators\UuidValidator::className();

