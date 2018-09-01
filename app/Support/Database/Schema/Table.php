<?php

namespace App\Support\Database\Schema;

use Doctrine\DBAL\Schema\Table as SchemaTable;

class Table extends SchemaTable
{
    public function toArray()
    {
        return [
            'name' => $this->_name,
            'oldName' => $this->_name,
            'columns' => $this->exportColumnsToArray(),
            'indexes' => $this->exportIndexesToArray(),
            'primaryKeyName' => $this->_primaryKeyName,
            'foreignKeys' => $this->exportForeignKeysToArray(),
            'options' => $this->_options,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function exportColumnsToArray()
    {
        $exportedColumns = [];

        foreach ($this->getColumns() as $name => $column) {
            $exportedColumns[] = Column::toArray($column);
        }

        return $exportedColumns;
    }

    public function exportIndexesToArray()
    {
        $exportedIndexes = [];

        foreach ($this->getIndexes() as $name => $index) {
            $indexArr = Index::toArray($index);
            $indexArr['table'] = $this->_name;
            $exportedIndexes[] = $indexArr;
        }

        return $exportedIndexes;
    }

    public function exportForeignKeysToArray()
    {
        $exportedForeignKeys = [];

        foreach ($this->getForeignKeys() as $name => $fk) {
            $exportedForeignKeys[$name] = ForeignKey::toArray($fk);
        }

        return $exportedForeignKeys;
    }
}
