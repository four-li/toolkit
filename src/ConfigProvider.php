<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
namespace FourLi\Toolkit;

use FourLi\Toolkit\Commands\SchemaCommand;
use FourLi\Toolkit\Listeners\QueryExecutedListener;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Filesystem\Filesystem;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
                SchemaCommand::class,
            ],
            'listeners' => [
                QueryExecutedListener::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                // autoload 配置
                [
                    'id' => 'toolkit config',
                    'description' => '混合工具包内的配置.',
                    'source' => __DIR__ . '/../publish/toolkit.php',
                    'destination' => BASE_PATH . '/config/autoload/toolkit.php',
                ],
                [
                    'id' => 'snowflake config',
                    'description' => '雪花算法依赖组件的配置',
                    'source' => __DIR__ . '/../publish/snowflake.php',
                    'destination' => BASE_PATH . '/config/autoload/snowflake.php',
                ],
                // 数据库实体配置 migrations
                [
                    'id' => 'cdebug entity',
                    'description' => 'cdebug entity magiration..',
                    'source' => __DIR__ . '/../publish/migrations/ToolkitInitCdebug.php',
                    'destination' => $this->getMigrationFileName('cdebug'),
                ],
                [
                    'id' => 'sqllog entity',
                    'description' => 'sqllog entity magiration..',
                    'source' => __DIR__ . '/../publish/migrations/ToolkitInitSqlLog.php',
                    'destination' => $this->getMigrationFileName('sql_log'),
                ],
            ],
        ];
    }

    protected function getMigrationFileName(string $server): string
    {
        $timestamp = date('Y_m_d_His');
        $filesystem = new Filesystem();
        return Collection::make(BASE_PATH . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $server) {
                return $filesystem->glob($path . '*_toolkit_init_' . $server . '.php');
            })->push(BASE_PATH . "/migrations/{$timestamp}_toolkit_init_" . $server . '.php')
            ->first();
    }
}
