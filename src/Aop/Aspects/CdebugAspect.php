<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
namespace FourLi\Toolkit\Aop\Aspects;

use App\Model\Toolkit\SysToolkitCdebug;
use App\Model\Toolkit\SysToolkitCdebugExt;
use FourLi\Toolkit\Aop\Annotations\Cdebug;
use FourLi\Toolkit\Utils;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Utils\Codec\Json;

#[Aspect]
class CdebugAspect extends \Hyperf\Di\Aop\AbstractAspect
{
    public $annotations = [Cdebug::class];

    /** @var StdoutLoggerInterface */
    private $stdout;

    /** @var ConfigInterface */
    private $config;

    public function __construct(StdoutLoggerInterface $stdoutLogger, ConfigInterface $config)
    {
        $this->stdout = $stdoutLogger;
        $this->config = $config;
    }

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $annotationMethods = $proceedingJoinPoint->getAnnotationMetadata()->method;

        if (isset($annotationMethods[Cdebug::class])) {
            /** @var Cdebug $cdebug */
//            $cdebug = $annotationMethods[Cdebug::class];
//            $eventName = $proceedingJoinPoint->className . '::' . $proceedingJoinPoint->methodName;

            $config = $this->config->get('toolkit.cdebug');

            // 未开启
            if ($config['enable'] !== true) {
                return $proceedingJoinPoint->process();
            }

            try {
                $startMem = memory_get_usage();
                $startms = microtime(true);
                $result = $proceedingJoinPoint->process();
                $success = true;
            } catch (\Exception $e) {
                $success = false;
            } finally {
                $usedMemory = (memory_get_usage() - $startMem);
                $usedTime = microtime(true) - $startms;
                if ($success) {
                    $stdlog = 'info';
                    $retval = 1;
                } else {
                    $stdlog = 'error';
                    $retval = -1;
                    $result = [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'msg' => $e->getMessage(),
                        'trade' => substr($e->getTraceAsString(), 0, 255),
                    ];
                }

                // 终端输出
                if ($config['stdout'] === true) {
                    $message = '调试方法' . $proceedingJoinPoint->methodName . ' 消耗内存:[' . self::getSize($usedMemory) . ' ], 消耗时间:[' . round($usedTime, 3) . ' MS]';
                    $this->stdout->{$stdlog}($message);
                }

                // 记录日志
                if ($config['db'] === true) {
                    $params = $proceedingJoinPoint->arguments['keys'];
                    if (class_exists('App\Model\Toolkit\SysToolkitCdebug')) {
                        $logModel = new SysToolkitCdebug();
                        $logModel->setSerialno(Utils::genSnowflakeId())
                            ->setClass($proceedingJoinPoint->className)
                            ->setMethod($proceedingJoinPoint->methodName)
                            ->setRetval($retval)
                            ->setConsumeTime(ceil($usedTime * 1000))
                            ->setConsumeMemory($usedMemory);
                        $logModel->save();
                    }

                    $format = [];
                    foreach ($params as $var => $val) {
                        if (is_object($val)) {
                            $val = [
                                'obj' => get_class($val),
                                'property' => Json::decode(Json::encode($val)),
                            ];
                        }
                        $format['$' . $var] = $val;
                    }
                    if (class_exists('App\Model\Toolkit\SysToolkitCdebugExt')) {
                        $detailModel = new SysToolkitCdebugExt();
                        $detailModel->setSerialno($logModel->getSerialno())
                            ->setParams(Json::encode($format))
                            ->setResult(Json::encode($result));
                        $detailModel->save();
                    }
                }

                if (! $success) {
                    throw $e;
                }
                return $result;
            }
        }

        return $proceedingJoinPoint->process();
    }

    private static function getSize($num)
    {
        $p = 0;
        $format = 'bytes';
        if ($num > 0 && $num < 1024) {
            $p = 0;
            return number_format($num) . ' ' . $format;
        }
        if ($num >= 1024 && $num < pow(1024, 2)) {
            $p = 1;
            $format = 'KB';
        }
        if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
            $p = 2;
            $format = 'MB';
        }
        if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
            $p = 3;
            $format = 'GB';
        }
        if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
            $p = 3;
            $format = 'TB';
        }
        $num /= pow(1024, $p);
        return number_format($num, 3) . ' ' . $format;
    }
}
