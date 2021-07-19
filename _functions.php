<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
if (! function_exists('_dump')) {
    /**
     * - 【 打印变量 】.
     */
    function _dump(...$args)
    {
        \FourLi\Toolkit\Utils::dumper(...$args);
    }
}

if (! function_exists('_instr')) {
    /**
     * - 【 判断一个字符串是否在另一个字符串中出现 】.
     * @param mixed $search
     * @param mixed $target
     */
    function _instr($search, $target): bool
    {
        if (strpos(strval($target), strval($search)) === false) {
            return false;
        }
        return true;
    }
}
