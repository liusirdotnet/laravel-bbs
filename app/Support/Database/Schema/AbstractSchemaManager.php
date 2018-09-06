<?php

namespace App\Support\Database\Schema;

use Doctrine\DBAL\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class AbstractSchemaManager
{
    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return static::getDoctrineSchemaManager()->$method(...$arguments);
    }

    /**
     * Get the Doctrine DBAL schema manager for the connection.
     *
     * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public static function getDoctrineSchemaManager(): \Doctrine\DBAL\Schema\AbstractSchemaManager
    {
        return DB::connection()->getDoctrineSchemaManager();
    }

    /**
     * Get the Doctrine DBAL database connection instance.
     *
     * @return \Doctrine\DBAL\Connection
     */
    public static function getDatabaseConnection(): Connection
    {
        return DB::connection()->getDoctrineConnection();
    }

    /**
     * Returns true if all the given tables exist.
     *
     * @param string $tableName
     *
     * @return bool
     */
    public static function tableExists(string $tableName)
    {
        return static::getDoctrineSchemaManager()->tablesExist((array)$tableName);
    }

    /**
     * @return array
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function listTables(): array
    {
        $tables = [];

        foreach (static::getDoctrineSchemaManager()->listTableNames() as $tableName) {
            $tables[$tableName] = static::listTableDetails($tableName);
        }

        return $tables;
    }

    /**
     * @param string $table
     *
     * @return \App\Support\Database\Schema\Table
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function listTableDetails($table): Table
    {
        $columns = static::getDoctrineSchemaManager()->listTableColumns($table);

        $foreignKeys = [];
        if (static::getDoctrineSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
            $foreignKeys = static::getDoctrineSchemaManager()->listTableForeignKeys($table);
        }

        $indexes = static::getDoctrineSchemaManager()->listTableIndexes($table);

        return new Table($table, $columns, $indexes, $foreignKeys, false, []);
    }

    /**
     * @param string $tableName
     *
     * @return \Illuminate\Support\Collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function describeTable(string $tableName): Collection
    {
        /** @var \App\Support\Database\Schema\Table $talbe */
        $table = static::listTableDetails($tableName);

        return collect($table->getColumns())->map(function ($column) use ($table) {
            $columns = Column::toArray($column);
            $columns['field'] = $columns['name'];
            $columns['type'] = $columns['type']['name'];
            $columns['indexes'] = [];
            $columns['key'] = null;

            return $columns;
        });
    }

    /**
     * @param string $tableName
     *
     * @return array
     */
    public static function listTableColumnNames(string $tableName): array
    {
        $columnNames = [];

        foreach (static::getDoctrineSchemaManager()->listTableColumns($tableName) as $column) {
            $columnNames[] = $column->getName();
        }

        return $columnNames;
    }
}
