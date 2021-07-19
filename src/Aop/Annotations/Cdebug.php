<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
namespace FourLi\Toolkit\Aop\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 *
 * 用于监控代码执行的元方法
 */
class Cdebug extends \Hyperf\Di\Annotation\AbstractAnnotation
{
}
