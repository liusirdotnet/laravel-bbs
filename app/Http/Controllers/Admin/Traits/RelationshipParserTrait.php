<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\DataType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

trait RelationshipParserTrait
{
    protected $relationFields = [];

    protected function resolveRelations($dataTypeContent, DataType $dataType)
    {
        if ($dataTypeContent instanceof LengthAwarePaginator) {
            $dataTypeCollection = $dataTypeContent->getCollection();
        } elseif ($dataTypeContent instanceof Model) {
            return $this->relationToLink($dataTypeContent, $dataType);
        } else {
            $dataTypeCollection = $dataTypeContent;
        }

        $dataTypeContent->transform(function ($item) use ($dataType) {
            return $this->relationToLink($item, $dataType);
        });

        return $dataTypeContent instanceof LengthAwarePaginator
            ? $dataTypeContent->setCollection($dataTypeCollection)
            : $dataTypeContent;
    }

    protected function removeRelationshipField(
        DataType $dataType,
        $type = 'access'
    ) {
        $forgetKeys = [];
        foreach ($dataType->{$type . 'Rows'} as $key => $row) {
            if ($row->type === 'relationship') {
                $options = json_decode($row->details);
                $relationshipField = @$options->column;
                $array = $dataType->{$type . 'Rows'}
                    ->where('field', '=', $relationshipField)
                    ->toArray();
                $keyInCollection = key($array);
                $forgetKeys[] = $keyInCollection;
            }
        }

        foreach ($forgetKeys as $forgetKey) {
            $dataType->{$type . 'Rows'}->forget($forgetKey);
        }
    }

    protected function getRelationships(DataType $dataType)
    {
        $relationships = [];

        $dataType->accessRows->each(function ($item) use (& $relationships) {
            $details = json_decode($item->details);

            if (isset($details->relationship, $item->field)) {
                $relation = $details->relationship;

                if (isset($relation->method)) {
                    $method = $relation->method;
                    $this->relationFields[$method] = $item->field;
                } else {
                    $method = camel_case($item->field);
                }

                $relationships[$method] = function ($query) use ($relation) {
                    // select only what we need
                    if (isset($relation->method)) {
                        return $query;
                    } else {
                        $query->select($relation->key, $relation->label);
                    }
                };
            }
        });

        return $relationships;
    }

    protected function relationToLink(Model $item, DataType $dataType)
    {
        $relations = $item->getRelations();

        if (! empty($relations) && array_filter($relations)) {
            foreach ($relations as $field => $relation) {
                if (isset($this->relationFields[$field])) {
                    $field = $this->relationFields[$field];
                } else {
                    $field = snake_case($field);
                }

                $bread_data = $dataType->accessRows
                    ->where('field', $field)
                    ->first();
                $relationData = json_decode($bread_data->details)->relationship;

                if ($bread_data->type === 'select_multiple') {
                    $relationItems = [];
                    foreach ($relation as $model) {
                        $relationItem = new \stdClass();
                        $relationItem->{$field} = $model[$relationData->label];
                        if (isset($relationData->page_slug)) {
                            $id = $model->id;
                            $relationItem->{$field . '_page_slug'} =
                                url($relationData->page_slug, $id);
                        }
                        $relationItems[] = $relationItem;
                    }
                    $item[$field] = $relationItems;
                    continue; // Go to the next relation
                }

                if (! \is_object($item[$field])) {
                    $item[$field] = $relation[$relationData->label];
                } else {
                    $tmp = $item[$field];
                    $item[$field] = $tmp;
                }
                if (isset($relationData->page_slug) && $relation) {
                    $id = $relation->id;
                    $item[$field . '_page_slug'] = url($relationData->page_slug, $id);
                }
            }
        }

        return $item;
    }

    public function deleteBreadImages($data, $rows)
    {
        foreach ($rows as $row) {
            if ($data->{$row->field} !== config('admin.user.default_avatar')) {
                $this->deleteFileIfExists($data->{$row->field});
            }

            $options = json_decode($row->details);

            if (isset($options->thumbnails)) {
                foreach ($options->thumbnails as $thumbnail) {
                    $ext = explode('.', $data->{$row->field});
                    $extension = '.' . $ext[count($ext) - 1];

                    $path = str_replace($extension, '', $data->{$row->field});

                    $thumb_name = $thumbnail->name;

                    $this->deleteFileIfExists($path . '-' . $thumb_name . $extension);
                }
            }
        }
    }

    public function deleteFileIfExists($path)
    {
        if (Storage::disk(config('admin.storage.disk'))->exists($path)) {
            Storage::disk(config('admin.storage.disk'))->delete($path);
        }
    }
}
