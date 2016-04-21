g<?php

use yii\db\Migration;

class m160103_175335_init extends Migration
{
    public function safeUp()
    {
        $this->execute('
            SET search_path = public
        ');

        $this->execute('
            CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
        ');

        $this->execute('
            CREATE TABLE public.user (
                id UUID NOT NULL PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                auth_key VARCHAR(64) NOT NULL UNIQUE,
                token UUID NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL
            )
        ');

        $this->execute('
            CREATE TABLE public.bill (
                id UUID NOT NULL PRIMARY KEY,
                user_id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                balance DOUBLE PRECISION NOT NULL DEFAULT 0,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                CONSTRAINT bill_2_user_fk FOREIGN KEY (user_id) REFERENCES public.user(id)
                     ON DELETE CASCADE
                     ON UPDATE CASCADE
                     NOT DEFERRABLE,
                UNIQUE(user_id, name)
            )
        ');

        $this->execute('
            CREATE TABLE public.category (
                id UUID NOT NULL PRIMARY KEY,
                user_id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                is_income BOOLEAN NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                CONSTRAINT category_2_user_fk FOREIGN KEY (user_id) REFERENCES public.user(id)
                     ON DELETE CASCADE
                     ON UPDATE CASCADE
                     NOT DEFERRABLE,
                UNIQUE(user_id, name)
            )
        ');

        $this->execute('
            CREATE TABLE public.transaction (
                id UUID NOT NULL PRIMARY KEY,
                user_id UUID NOT NULL,
                category_id UUID,
                bill_id UUID NOT NULL,
                amount DOUBLE PRECISION NOT NULL CHECK (amount > 0),
                type SMALLINT NOT NULL,
                comment TEXT,
                source_bill_id UUID,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                datetime_local TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                CONSTRAINT transaction_2_user_fk FOREIGN KEY (user_id) REFERENCES public.user(id)
                     ON DELETE CASCADE
                     ON UPDATE CASCADE
                     NOT DEFERRABLE,
                CONSTRAINT transaction_2_category_fk FOREIGN KEY (category_id) REFERENCES public.category(id)
                     ON DELETE CASCADE
                     ON UPDATE CASCADE
                     NOT DEFERRABLE,
                CONSTRAINT transaction_2_bill_fk FOREIGN KEY (bill_id) REFERENCES public.bill(id)
                     ON DELETE CASCADE
                     ON UPDATE CASCADE
                     NOT DEFERRABLE,
                CONSTRAINT transaction_2_source_bill_fk FOREIGN KEY (source_bill_id) REFERENCES public.bill(id)
                     ON DELETE CASCADE
                     ON UPDATE CASCADE
                     NOT DEFERRABLE
            )
        ');

    }

    public function safeDown()
    {
        return false;
    }
}
