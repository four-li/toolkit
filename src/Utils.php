<?php
declare(strict_types=1);
// date: 2021/7/16 author: four-li

namespace FourLi\Toolkit;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\ApplicationContext;

class Utils
{
    public static function container()
    {
        return ApplicationContext::getContainer();
    }

    /**
     * - 【 控制台输出 】
     */
    public static function stdLogger($msg, $type = 'info')
    {
        if (is_string($msg)) {
            self::container()->get(StdoutLoggerInterface::class)->{$type}($msg);
        }
        return true;
    }

    /**
     * - 【 打印调试 】
     */
    public static function dumper(...$args)
    {
        if (function_exists('dump')) {
            if (self::container()->get(ConfigInterface::class)->get('app_env') !== 'prod') {
                if (true) {
                    $trace = debug_backtrace();
                    self::container()->get(StdoutLoggerInterface::class)->notice(
                        '_'
                    );
                }
                dump(...$args);
            }
        }
    }
}