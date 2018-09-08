<?php

namespace App\Support\Database\Types\Common;

use Doctrine\DBAL\Types\FloatType;

class DoubleType extends FloatType
{
    public const NAME = 'double';

    public function getName()
    {
        return static::NAME;
    }
}
