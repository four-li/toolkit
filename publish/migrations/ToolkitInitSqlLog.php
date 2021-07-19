<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
use Hyperf\Database\Schema\Blueprint;

class ToolkitInitSqlLog extends \FourLi\Toolkit\Components\MigrationSchema\BaseMigration
{
    public function configure(): array
    {
        $enable = config('toolkit.sqllog.enable') && config('toolkit.sqllog.db');

        if (! $enable) {
            \FourLi\Toolkit\Utils::stdLogger('未开启toolkit.sqllog.db', 'notice');
            return [];
        }

        return [
            'sys_toolkit_sqllog' => [
                'fn' => function (Blueprint $table) {
                    $table->comment('执行sql日志');
                    $this->_increment($table);
                    $table->bigInteger('serialno', false, true)->comment('唯一的序列号')->unique();
                    $table->string('sql', 255)->comment('未绑定参数的sql');
                    $table->string('sql_type', 10)->comment('sql类型');
                    $table->bigInteger('consume_time', false, true)->comment('耗时（微秒）= 秒/1024/1024');
                    $this->_autoTimestramp($table);
                },
            ],
            'sys_toolkit_sqllog_ext' => [
                'fn' => function (Blueprint $table) {
                    $table->comment('执行sql详情日志');
                    $this->_increment($table);
                    $table->bigInteger('serialno', false, true)->comment('sys_sql_log表的序列号')->index();
                    $table->text('full_sql')->comment('执行的完整sql语句');
                },
            ],
        ];
    }
}
