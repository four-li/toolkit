<?php
declare(strict_types=1);
// date: 2021/7/15 author: four-li

namespace FourLi\Toolkit\Aop\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 *
 * 用于监控代码执行的元方法
 */
class Cdebug extends \Hyperf\Di\Annotation\AbstractAnnotation
{
    /** @var bool 是否输信息出在控制台 */
    public $stdout = true;

    /** @var bool 是否写入文件日志 */
    public $write = true;

    /** @var bool 生产环境关闭 */
    public $prodClosed = true;
}
