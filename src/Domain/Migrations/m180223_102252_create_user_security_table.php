<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use PhpLab\Eloquent\Migration\Base\BaseCreateTableMigration;
use PhpLab\Eloquent\Migration\Enums\ForeignActionEnum;

class m180223_102252_create_user_security_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_security';
    protected $tableComment = 'Хэш пароля пользователя';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');

            $table->integer('identity_id')->comment('ID учетной записи');
            $table->string('password_hash')->comment('Хэш пароля');

            $table->unique('identity_id');
            $table
                ->foreign('identity_id')
                ->references('id')
                ->on($this->encodeTableName('user_identity'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
        };
    }

}
