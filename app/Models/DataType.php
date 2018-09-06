<?php

namespace App\Models;

use App\Support\Database\Schema\AbstractSchemaManager;
use Illuminate\Database\Eloquent\Model;
use App\Support\Facades\Admin;
use Illuminate\Support\Facades\DB;

class DataType extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
        'policy_name',
        'controller',
        'description',
        'generate_permissions',
        'server_side',
        'order_column',
        'order_display_column',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rows()
    {
        return $this->hasMany(Admin::getModelClass('DataRow'))->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessRows()
    {
        return $this->rows()->where('access', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addRows()
    {
        return $this->rows()->where('add', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function readRows()
    {
        return $this->rows()->where('read', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function editRows()
    {
        return $this->rows()->where('edit', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deleteRows()
    {
        return $this->rows()->where('delete', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|null|object
     */
    public function lastRow()
    {
        return $this->hasMany(Admin::getModelClass('DataRow'))
            ->orderBy('order', 'DESC')
            ->first();
    }

    /**
     * @param array $data
     * @param bool  $throw
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function updateDataType(array $data, $throw = false): bool
    {
        try {
            DB::beginTransaction();

            foreach (['generate_permissions', 'server_side'] as $field) {
                $data[$field] = isset($data[$field]) ? 1 : 0;
            }
            unset($data['server_side']);

            if ($this->fill($data)->save()) {
                $fields = $this->fields(array_get($data, 'name'));

                dd($data);
                foreach ($fields as $field) {
                    $dataRow = $this->rows()->firstOrNew(['field' => $field]);
                    foreach (['access', 'read', 'add', 'edit', 'delete'] as $check) {
                        $dataRow->{$check} = isset($data["field_{$check}_{$field}"]);
                    }
                    $dataRow->required = $data['field_required_' . $field];
                    $dataRow->field = $data['field_' . $field];
                    $dataRow->type = $data['field_input_type_' . $field];
                    $dataRow->details = $data['field_details_' . $field];
                    $dataRow->display_name = $data['field_display_name_' . $field];
                    $dataRow->order = (int)$data['field_order_' . $field];

                    dd($dataRow->toArray());
                    if ($dataRow->save()) {
                        throw new \Exception(__('未能保存字段 :field，正在回滚操作！', ['field' => $field]));
                    }
                }
                $this->rows()->whereNotIn('field', $fields)->delete();

                if ($this->generate_permissions) {
                    Admin::getModel('Permission')->generateFor($this->name);
                }
                DB::commit();

                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($throw) {
                throw $e;
            }
        }

        return false;
    }

    /**
     * @param string|null $name
     *
     * @return array
     */
    public function fields(string $name = null): array
    {
        if ($name === null) {
            $name = $this->name;
        }

        $fields = AbstractSchemaManager::listTableColumnNames($name);

        if ($extraFields = $this->extraFields()) {
            foreach ($extraFields as $field) {
                $fields[] = $field['Field'];
            }
        }

        return $fields;
    }

    public function extraFields()
    {
        if (empty(trim($this->model_name))) {
            return [];
        }

        $model = app($this->model_name);

        if (method_exists($model, 'adminFields')) {
            return $model->adminFields();
        }
    }
}
