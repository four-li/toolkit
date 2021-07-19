<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
namespace FourLi\Toolkit\Listeners;

use FourLi\Toolkit\Utils;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Str;

class QueryExecutedListener implements ListenerInterface
{
    public function listen(): array
    {
        return [QueryExecuted::class];
    }

    /**
     * @param QueryExecuted $event
     */
    public function process(object $event)
    {
        if ($event instanceof QueryExecuted) {
            $sql = $event->sql;
            if (! Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $key => $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }

            $method = substr($sql, 0, 6);
            if (! in_array($method, ['insert', 'delete', 'update', 'select'])) {
                $method = 'other';
            }

            $info = [
                'full_sql' => $sql,
                'sql' => $event->sql,
                'sql_type' => $method,
                'time' => $event->time,
            ];

            _dump(1111111);

//            Utils::redis()->rPush(SysConfigNode::SQL_LOG_INTO_DB . ':list', Json::encode($info));

            Utils::stdLogger(sprintf('[%s] %s', $event->time, $sql), 'notice');
        }
    }
}
