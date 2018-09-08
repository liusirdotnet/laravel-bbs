<?php

namespace App\Support\Database\Platform;

use App\Support\Database\Types\Type;
use Illuminate\Support\Collection;

abstract class Mysql extends AbstractPlatform
{
    public static function getTypes(Collection $collect)
    {
        $collect->forget([
            'real',
            'int',
            'string',
            'numeric',
        ]);

        return $collect;
    }

    public static function registerCustomTypeOptions()
    {
        Type::registerCustomOption(Type::NOT_SUPPORTED, true, ['enum', 'set']);
        Type::registerCustomOption(Type::NOT_SUPPORT_INDEX, true, '*.test');
        Type::registerCustomOption(Type::NOT_SUPPORT_INDEX, true, '*blob');
    }
}
