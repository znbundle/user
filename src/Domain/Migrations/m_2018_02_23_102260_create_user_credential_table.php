<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnCore\Db\Migration\Base\BaseCreateTableMigration;
use ZnCore\Db\Migration\Enums\ForeignActionEnum;

class m_2018_02_23_102260_create_user_credential_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_credential';
    protected $tableComment = 'Аутентификация пользователя';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->integer('identity_id')->comment('ID учетной записи');
            $table->string('type')->comment('Тип аутентификации');
            $table->string('credential')->comment('Учетная запись');
            $table->string('validation')->comment('Хэш пароля');

            $table->unique(['type', 'credential']);
            $table
                ->foreign('identity_id')
                ->references('id')
                ->on($this->encodeTableName('user_identity'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
        };
    }

}
