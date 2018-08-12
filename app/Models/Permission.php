<?php

namespace App\Models;

use App\Models\Traits\HasRelationshipTrait;
use App\Support\Facades\Admin;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasRelationshipTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->hasMany(Admin::getModelClass('Role'));
    }

    public static function generate($table)
    {
        self::firstOrCreate(['action' => 'access_' . $table, 'table_name' => $table]);
        self::firstOrCreate(['action' => 'read_' . $table, 'table_name' => $table]);
        self::firstOrCreate(['action' => 'edit_' . $table, 'table_name' => $table]);
        self::firstOrCreate(['action' => 'add_' . $table, 'table_name' => $table]);
        self::firstOrCreate(['action' => 'delete_' . $table, 'table_name' => $table]);
    }
}
