<?php
declare(strict_types=1);
// date: 2021/7/16 author: four-li

namespace FourLi\Toolkit\Components\MigrationSchema;

interface MigrationInterface
{
    public function configure(): array;
}