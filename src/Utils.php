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
use Hyperf\Redis\RedisFactory;
use Hyperf\Snowflake\IdGeneratorInterface;
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
        $config = self::container()->get(ConfigInterface::class);
        $env = $config->get('toolkit.app_env', 'prod');

        if ($env !== 'prod') {
            if (function_exists('dump')) {
                if ($config->get('toolkit.dump_trade')) {
                    $trace = debug_backtrace()[0];
                    self::stdLogger(sprintf('dumper in %s (%s)', $trace['file'], $trace['line']), 'notice');
                }
                dump(...$args);
            } else {
                self::stdLogger('未安装dump-server, 请执行composer require symfony/var-dumper --dev -vvv', 'notice');
            }
        }
    }

    /**
     * - 【 生成id 】.
     */
    public static function genSnowflakeId()
    {
        return self::container()->get(IdGeneratorInterface::class)->generate();
    }

    /**
     * - 【 redis 】.
     * @param mixed $redisPoolName
     */
    public static function redis($redisPoolName = 'default')
    {
        return ApplicationContext::getContainer()->get(RedisFactory::class)->get($redisPoolName);
    }

    /**
     * - 【 日志 】.
     * @param mixed $channel
     */
    public static function logger($channel = 'app')
    {
        return ApplicationContext::getContainer()->get(\Hyperf\Logger\LoggerFactory::class)->get($channel);
    }
}
