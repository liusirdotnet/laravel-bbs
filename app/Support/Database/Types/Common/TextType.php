<?php

namespace App\Support\Database\Types\Common;

use App\Support\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TextType extends Type
{
    public const NAME = 'text';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'text';
    }
}
