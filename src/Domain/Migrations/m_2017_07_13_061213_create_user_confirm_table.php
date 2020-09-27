<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnLib\Migration\Domain\Base\BaseCreateTableMigration;

class m_2017_07_13_061213_create_user_confirm_table extends BaseCreateTableMigration
{

    protected $tableName = 'user_confirm';
    protected $tableComment = 'Код активации';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->string('login');
            $table->string('action');
            $table->string('code');
            $table->boolean('is_activated');
            $table->text('data')->nullable();
            $table->integer('expire');
            $table->dateTime('created_at');

            $table->unique(['login', 'action']);
        };
    }

}
