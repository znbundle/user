<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use PhpLab\Eloquent\Migration\Base\BaseCreateTableMigration;

class m_2014_10_14_100000_create_user_table extends BaseCreateTableMigration
{

    protected $tableName = 'fos_user';
    protected $tableComment = 'Пользователи (FOS)';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->string('username', 180);
            $table->string('username_canonical', 180);
            $table->string('email', 180);
            $table->string('email_canonical', 180);
            $table->tinyInteger('enabled');
            $table->string('salt', 255)->nullable();
            $table->string('password', 255);
            $table->dateTime('last_login')->nullable();
            $table->string('confirmation_token', 180)->nullable();
            $table->dateTime('password_requested_at')->nullable();
            $table->longText('roles');

            $table->unique('username_canonical');
            $table->unique('email_canonical');
            $table->unique('confirmation_token');
        };
    }

}
