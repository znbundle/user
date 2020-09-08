<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnCore\Db\Migration\Base\BaseCreateTableMigration;

class m170104_202556_create_user_identity_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_identity';
    protected $tableComment = 'Аккаунт пользователя';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->string('login')->comment('Логин');
            $table->integer('status')->comment('Статус');
            $table->dateTime('created_at')->comment('Дата создания');
            $table->dateTime('updated_at')->comment('Дата обновления');

            $table->unique('login');
        };
    }

}
