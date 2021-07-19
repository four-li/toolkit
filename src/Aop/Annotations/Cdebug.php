<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */

namespace FourLi\Toolkit\Aop\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Cdebug extends \Hyperf\Di\Annotation\AbstractAnnotation
{
}
