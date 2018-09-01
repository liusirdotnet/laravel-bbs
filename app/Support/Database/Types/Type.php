<?php

namespace App\Support\Database\Types;

use Doctrine\DBAL\Types\Type as DoctrineType;

abstract class Type extends DoctrineType
{
    public const NAME = 'UNDEFINED_TYPE_NAME';
    public const NOT_SUPPORTED = 'notSupported';
    public const NOT_SUPPORT_INDEX = 'notSupportIndex';

    protected static $customTypesRegistered = false;
    protected static $platformTypeMapping = [];
    protected static $allTypes = [];
    protected static $platformTypes = [];
    protected static $customTypeOptions = [];
    protected static $typeCategories = [];

    public static function toArray(DoctrineType $type)
    {
        $customOptions = isset($type->customOptions) ? $type->customOptions : [];

        return array_merge(['name' => $type->getName(),], $customOptions);
    }
}
