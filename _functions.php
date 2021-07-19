<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
if (! function_exists('_dump')) {
    function _dump(...$args)
    {
        \FourLi\Toolkit\Utils::dumper(...$args);
    }
}
