<?php

namespace App\Support\Database\Schema;

use App\Support\Database\Types\Type;
use Doctrine\DBAL\Schema\Column as DoctrineColumn;

abstract class Column
{
    public static function toArray(DoctrineColumn $column)
    {
        $columns = $column->toArray();
        $columns['type'] = Type::toArray($columns['type']);
        $columns['oldName'] = $columns['name'];
        $columns['null'] = $columns['notnull'] ? 'NO' : 'YES';
        $columns['extra'] = static::getExtra($column);
        $columns['composite'] = false;

        return $columns;
    }

    public static function getExtra(DoctrineColumn $column)
    {
        $extra = '';
        $extra .= $column->getAutoincrement() ? 'auto_increment' : '';

        // Todo: Add Extra stuff like mysql 'onUpdate' etc...

        return $extra;
    }
}
