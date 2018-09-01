<?php

namespace App\Support\Database\Schema;

use Doctrine\DBAL\Schema\ForeignKeyConstraint as DoctrineForeignKey;

abstract class ForeignKey
{
    public static function toArray(DoctrineForeignKey $fk)
    {
        return [
            'name' => $fk->getName(),
            'localTable' => $fk->getLocalTableName(),
            'localColumns' => $fk->getLocalColumns(),
            'foreignTable' => $fk->getForeignTableName(),
            'foreignColumns' => $fk->getForeignColumns(),
            'options' => $fk->getOptions(),
        ];
    }
}
