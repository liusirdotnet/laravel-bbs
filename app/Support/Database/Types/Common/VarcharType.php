<?php

namespace App\Support\Database\Types\Common;

use Doctrine\DBAL\Types\StringType as DoctrineStringType;

class VarcharType extends DoctrineStringType
{
    public const NAME = 'varchar';

    public function getName()
    {
        return static::NAME;
    }
}
