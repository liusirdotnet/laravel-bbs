<?php

namespace App\Support\Database\Schema;

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
    public static function getDoctrineSchemaManager()
    {
        return DB::connection()->getDoctrineSchemaManager();
    }

    /**
     * Get the Doctrine DBAL database connection instance.
     *
     * @return \Doctrine\DBAL\Connection
     */
    public static function getDatabaseConnection()
    {
        return DB::connection()->getDoctrineConnection();
    }

    /**
     * Returns true if all the given tables exist.
     *
     * @param string $table
     *
     * @return bool
     */
    public static function tableExists($table)
    {
        return static::getDoctrineSchemaManager()->tablesExist((array) $table);
    }

    /**
     * @return array
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function listTables()
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
    public static function listTableDetails($table)
    {
        $columns = static::getDoctrineSchemaManager()->listTableColumns($table);

        $foreignKeys = [];
        if (static::getDoctrineSchemaManager()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
            $foreignKeys = static::getDoctrineSchemaManager()->listTableForeignKeys($table);
        }

        $indexes = static::getDoctrineSchemaManager()->listTableIndexes($table);

        return new Table($table, $columns, $indexes, $foreignKeys, false, []);
    }

    public static function describeTable($tableName)
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
}
