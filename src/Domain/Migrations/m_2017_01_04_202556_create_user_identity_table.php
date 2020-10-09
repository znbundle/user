<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnLib\Migration\Domain\Base\BaseCreateTableMigration;

class m_2017_01_04_202556_create_user_identity_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_identity';
    protected $tableComment = 'Аккаунт пользователя';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->string('login')->comment('Логин');
            $table->integer('status')->comment('Статус');
            $table->dateTime('created_at')->comment('Время создания');
            $table->dateTime('updated_at')->comment('Время обновления');

            $table->unique('login');
        };
    }

}
