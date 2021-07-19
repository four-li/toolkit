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
            'listeners' => [],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'toolkit config',
                    'description' => 'The config for four-li/toolkit.',
                    'source' => __DIR__ . '/../publish/toolkit.php',
                    'destination' => BASE_PATH . '/config/autoload/toolkit.php',
                ],
                // 数据库 migrations
                [
                    'id' => 'cdebug entity',
                    'description' => 'cdebug entity magiration..',
                    'source' => __DIR__ . '/../publish/migrations/cdebug.php',
                    'destination' => $this->getMigrationFileName('cdebug'),
                ],
            ],
        ];
    }

    protected function getMigrationFileName(string $server): string
    {
        $timestamp = date('Y_m_d_His');
        $filesystem = new Filesystem();
        return Collection::make(BASE_PATH . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path . '*_create_permission_tables.php');
            })->push(BASE_PATH . "/migrations/{$timestamp}_toolkit_init_" . $server . '.php')
            ->first();
    }
}
