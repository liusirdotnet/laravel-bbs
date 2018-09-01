<?php

namespace App\Support\Database\Schema;

use Doctrine\DBAL\Schema\Index as DoctrineIndex;

abstract class Index
{
    public const PRIMARY = 'PRIMARY';
    public const UNIQUE = 'UNIQUE';
    public const INDEX = 'INDEX';

    public static function toArray(DoctrineIndex $index)
    {
        $name = $index->getName();
        $columns = $index->getColumns();

        return [
            'name' => $name,
            'oldName' => $name,
            'columns' => $columns,
            'type' => static::getType($index),
            'isPrimary' => $index->isPrimary(),
            'isUnique' => $index->isUnique(),
            'isComposite' => \count($columns) > 1,
            'flags' => $index->getFlags(),
            'options' => $index->getOptions(),
        ];
    }

    public static function getType(DoctrineIndex $index)
    {
        if ($index->isPrimary()) {
            return static::PRIMARY;
        } elseif ($index->isUnique()) {
            return static::UNIQUE;
        } else {
            return static::INDEX;
        }
    }

    public static function availableTypes()
    {
        return [
            static::PRIMARY,
            static::UNIQUE,
            static::INDEX,
        ];
    }
}
