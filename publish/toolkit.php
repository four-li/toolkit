<?php

declare(strict_types=1);
/**
 * You know, for fast.
 *
 * @link     https://www.open.ctl.pub
 * @document https://doc.open.ctl.pub
 */
return [
    'app_env' => env('APP_ENV', 'prod'),

    // cdeubg 通过注解简单分析方法的内存和耗时
    'cdebug' => [
        // 全局关闭
        'enable' => true,
        // 启用记录cdebug信息到数据库 依赖模型, 开启用请使用命令创建数据库
        'db' => true,
        // 是否控制台输出信息
        'stdout' => true,
        // prod 环境建议（默认关闭）
        'prod_enable' => false,
    ],

    'sqllog' => [
        // 全局开启
        'enable' => true,
        // 启用记录cdebug信息到数据库 依赖模型, 开启用请使用命令创建数据库
        'db' => true,
        // 是否控制台输出信息
        'stdout' => true,
    ],
];
