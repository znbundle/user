<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use PhpLab\Eloquent\Migration\Base\BaseCreateTableMigration;

class m170713_061213_create_user_confirm_table extends BaseCreateTableMigration
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
            $table->text('data');
            $table->integer('expire');
            $table->dateTime('created_at');

            $table->unique(['login', 'action']);
        };
    }

}
