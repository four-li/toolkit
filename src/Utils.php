<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
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
     * - 【 控制台输出 】.
     * @param mixed $msg
     * @param mixed $type
     */
    public static function stdLogger($msg, $type = 'info')
    {
        if (is_string($msg)) {
            self::container()->get(StdoutLoggerInterface::class)->{$type}($msg);
        }
        return true;
    }

    /**
     * - 【 打印调试 】.
     */
    public static function dumper(...$args)
    {
        if (self::container()->get(ConfigInterface::class)->get('app_env') !== 'prod') {
            if (function_exists('dump')) {
                if (true) {
                    $trace = debug_backtrace()[0];
                    self::stdLogger(sprintf('dumper in %s (%s)', $trace['file'], $trace['line']), 'notice');
                }
                dump(...$args);
            } else {
                self::stdLogger('未安装dump-server, 请执行composer require qiutuleng/hyperf-dump-server --dev -vvv', 'notice');
            }
        }
    }
}
