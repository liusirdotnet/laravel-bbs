<?php

namespace App\Support\Database\Types\Mysql;

use App\Support\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumType extends Type
{
    public const NAME = 'enum';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        throw new \Exception('Enum type is not supported');
    }
}
