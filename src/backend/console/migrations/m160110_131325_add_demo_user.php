<?php

use yii\db\Migration;

class m160110_131325_add_demo_user extends Migration
{
    public function safeUp()
    {
        $this->execute("
            INSERT INTO public.\"user\"
            (
                id,
                username,
                auth_key,
                token,
                password_hash,
                created_at,
                updated_at)
            VALUES (
                '" . Yii::$app->params['demo_user_id'] . "',
                '" . Yii::$app->params['demo_user_username'] . "',
                '695c8186-919c-4084-8640-3a28dba3e4de',
                '" . Yii::$app->params['demo_user_token'] . "',
                '" . Yii::$app->params['demo_user_password_hash'] . "',
                now(),
                now()
            )
        ");
    }

    public function safeDown()
    {
        return false;
    }
}
