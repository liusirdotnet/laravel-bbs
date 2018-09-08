<?php

namespace App\Support\Database\Types\Mysql;

use App\Support\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class BinaryType extends Type
{
    public const NAME = 'binary';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        $field['length'] = empty($field['length']) ? 255 : $field['length'];

        return "binary({$field['length']})";
    }
}
