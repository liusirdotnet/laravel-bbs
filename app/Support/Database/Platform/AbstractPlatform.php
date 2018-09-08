<?php

namespace App\Support\Database\Platform;

use Illuminate\Support\Collection;

abstract class AbstractPlatform
{
    public static function getPlatform($name)
    {
        $platform = __NAMESPACE__ . '\\' . ucfirst($name);

        if (! class_exists($platform)) {
            throw new \Exception("Platform {$name} doesn't exist");
        }

        return $platform;
    }

    public static function getPlatformTypes($name, Collection $collect)
    {
        $platform = static::getPlatform($name);

        return $platform::getTypes($collect);
    }

    public static function registerPlatformCustomTypeOptions($name)
    {
        $platform = static::getPlatform($name);

        return $platform::registerCustomTypeOptions();
    }
}
