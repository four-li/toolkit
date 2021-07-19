<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
use Hyperf\Database\Schema\Blueprint;

class InitCdebug extends \FourLi\Toolkit\Components\MigrationSchema\BaseMigration
{
    public function configure(): array
    {
        $enable = config('toolkit.cdebug.db');

        dump($enable);

        if ($enable !== true) {
            return [];
        }

        return [
            'sys_toolkit_cdebug' => [
                'fn' => function (Blueprint $table) {
                    $table->comment('cdebug日志');
                    $this->_increment($table);
                    $table->bigInteger('serialno', false, true)->comment('唯一的序列号')->unique();
                    $table->string('class', 100)->comment('执行程序的class')->default('');
                    $table->string('method', 50)->comment('执行程序的method')->default('');
                    $table->smallInteger('retval')->comment('执行结果返回值 -1 异常、错误; 1 执行成功');
                    $table->string('exception', 255)->comment('异常信息')->default('');
                    $table->bigInteger('consume_time')->comment('耗时：毫秒');
                    $table->bigInteger('consume_memory')->comment('耗内存：byte');
                    $this->_autoTimestramp($table);
                },
            ],
            'sys_toolkit_cdebug_ext' => [
                'fn' => function (Blueprint $table) {
                    $table->comment('cdebug日志分表详情');
                    $this->_increment($table);
                    $table->bigInteger('serialno', false, true)->comment('cdebug的序列号')->index();
                    $table->text('params')->comment('请求参数')->nullable();
                    $table->text('result')->comment('响应结果，如果是异常显示trace信息')->nullable();
                },
            ],
        ];
    }
}
