<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
namespace FourLi\Toolkit\Crontabs;

use App\Model\Toolkit\SysToolkitSqllog;
use App\Model\Toolkit\SysToolkitSqllogExt;
use FourLi\Toolkit\Utils;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Codec\Json;

class ScannerCrontab
{
    public function SqlLogScanner()
    {
        $profile = config('toolkit.sqllog');

        for ($i = 1; $i <= 20; ++$i) {
            $sqlLogs = [];
            $sqlDetailLogs = [];
            for ($j = 1; $j < 50; ++$j) {
                $json = Utils::redis()->lPop('jobs:sqllog:list');
                if (! $json) {
                    break;
                }
                if ($profile['enable'] !== true) {
                    break;
                }
                if ($profile['db'] !== true) {
                    break;
                }
                $info = Json::decode($json);
                $sno = Utils::genSnowflakeId();
                $sqlLogs[] = [
                    'sql' => substr($info['sql'] ?? '', 0, 255),
                    'sql_type' => $info['sql_type'] ?? '',
                    'consume_time' => $info['time'] * 1000,
                    'serialno' => $sno,
                    'app_created' => date('Y-m-d H:i:s'),
                    'app_modified' => date('Y-m-d H:i:s'),
                ];
                $sqlDetailLogs[] = [
                    'serialno' => $sno,
                    'full_sql' => $info['full_sql'] ?? '',
                ];
            }

            if ($sqlLogs && $sqlDetailLogs) {
                if (class_exists('SysToolkitSqllog') && class_exists('SysToolkitSqllogExt')) {
                    if (method_exists(SysToolkitSqllog::class, 'getTableName')) {
                        Db::table(SysToolkitSqllog::getTableName())->insert($sqlLogs);
                    }
                    if (method_exists(SysToolkitSqllogExt::class, 'getTableName')) {
                        Db::table(SysToolkitSqllogExt::getTableName())->insert($sqlDetailLogs);
                    }
                }
            }
        }
    }
}
