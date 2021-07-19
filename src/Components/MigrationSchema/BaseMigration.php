<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
namespace FourLi\Toolkit\Components\MigrationSchema;

use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

abstract class BaseMigration extends \Hyperf\Database\Migrations\Migration implements MigrationInterface
{
    public function configure(): array
    {
        return [];
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->configure() as $table => $config) {
            if (! Schema::hasTable($table)) {
                $closure = $config['fn'];
                Schema::create($table, $closure);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (array_keys($this->configure()) as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }

    protected function _arsort(Blueprint $table)
    {
        $table->smallInteger('arsort')->comment('权重，倒序值越大越靠前，取值范围：-32768~32767')->default(50);
    }

    protected function _increment(Blueprint $table)
    {
        $table->bigIncrements('id')->comment('主键');
    }

    protected function _autoTimestramp(Blueprint $table)
    {
        $table->timestamp('app_created')->comment('数据创建时间');
        $table->timestamp('app_modified')->comment('数据更新时间');
    }

    protected function _softDeleted(Blueprint $table)
    {
        $table->timestamp('app_deleted', 0)->nullable()->comment('软删除时间戳 未删除时为null');
    }
}
