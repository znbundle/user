<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;
use ZnLib\Db\Helpers\SqlHelper;
use ZnLib\Migration\Domain\Base\BaseColumnMigration;
use ZnLib\Migration\Domain\Base\BaseCreateTableMigration;
use ZnLib\Migration\Domain\Enums\ForeignActionEnum;

class m_2020_03_16_102260_add_column_expired_in_credential_table extends BaseColumnMigration
{

    protected $tableName = 'user_credential';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->dateTime('created_at')->nullable()->comment('Время создания');
            $table->dateTime('expired_at')->nullable()->comment('Годен до...');
        };
    }
}