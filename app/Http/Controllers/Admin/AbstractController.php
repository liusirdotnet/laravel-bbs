<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Contents\Types\CheckboxType;
use App\Support\Contents\Types\FileType;
use App\Support\Contents\Types\PasswordType;
use App\Support\Contents\Types\SelectType;
use App\Support\Contents\Types\TextType;
use App\Support\Contents\Types\TimestampType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class AbstractController extends Controller
{
    public function getSlug(Request $request)
    {
        if (isset($this->slug)) {
            $slug = $this->slug;
        } else {
            $slug = explode('.', $request->route()->getName())[1];
        }

        return $slug;
    }

    public function saveData(Request $request, $slug, Collection $rows, Model $model)
    {
        $select = [];

        foreach ($rows as $row) {
            $options = json_decode($row->details);

            if ($row->type !== 'checkbox'
                && ! $request->hasFile($row->field)
                && ! $request->has($row->field)
            ) {
                if ((isset($options->type) && $options->type !== 'belongsToMany')
                    || $row->field !== 'user_belongsto_role_relationship'
                ) {
                    continue;
                }
            }

            $content = $this->getContentFromType($request, $slug, $row, $options);

            if ($row->type === 'relationship' && $options->type !== 'belongsToMany') {
                $row->field = @$options->column;
            }

            if ($row->type === 'images'
                && null !== $content
                && isset($model->{$row->field})
            ) {
                $files = json_decode($model->{$row->field}, true);
                if (null !== $files) {
                    $content = json_encode(array_merge($files, json_decode($content)));
                }
            }

            if (null === $content) {
                if (\in_array(strtolower($row->type), ['password', 'file'], true)) {
                    $content = $model->{$row->field};
                }

                if (isset($model->{$row->type})
                    && null === $request->input($row->field)
                    && \in_array(strtolower($row->type), ['image', 'images'], true)
                ) {
                    $content = $model->{$row->field};
                }
            }

            if ($row->type === 'relationship' && $options->type === 'belongsToMany') {
                $select[] = [
                    'model'   => $options->model,
                    'table'   => $options->pivot_table,
                    'content' => $content,
                ];
            } else {
                $model->{$row->field} = $content;
            }
        }
        $model->save();


        if (\count($select)) {
            foreach ($select as $item) {
                $model->belongsToMany($item['model'], $item['table'])->sync($item['content']);
            }
        }

        return $model;
    }

    public function validateWithForm(array $request, $data, $name = null, $id = null)
    {
        $rules = $messages = $attributes = [];
        $isUpdate = $name && $id;

        $fieldsWithValidationRules = $this->getFieldsWithValidationRules($data);

        foreach ($fieldsWithValidationRules as $field) {
            $options = json_decode($field->details);
            $fieldRules = $options->validation->rule;
            $fieldName = $field->field;

            // Show the field's display name on the error message
            if (! empty($field->display_name)) {
                $customAttributes[$fieldName] = $field->display_name;
            }

            // Get the rules for the current field whatever the format it is in
            $rules[$fieldName] = \is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            // Fix Unique validation rule on Edit Mode
            if ($isUpdate) {
                foreach ($rules[$fieldName] as &$fieldRule) {
                    if (stripos($fieldRule, 'UNIQUE') !== false) {
                        $fieldRule = \Illuminate\Validation\Rule::unique($name)->ignore($id);
                    }
                }
            }

            // Set custom validation messages if any
            if (! empty($options->validation->messages)) {
                foreach ($options->validation->messages as $key => $msg) {
                    $messages["{$fieldName}.{$key}"] = $msg;
                }
            }
        }

        return Validator::make($request, $rules, $messages, $attributes);
    }

    public function getContentFromType(Request $request, $slug, $row, $options)
    {
        switch ($row->type) {
            case 'password':
                return (new PasswordType($request, $slug, $row, $options))->handle();
            case 'checkbox':
                return (new CheckboxType($request, $slug, $row, $options))->handle();
            case 'file':
                return (new FileType($request, $slug, $row, $options))->handle();
            case 'select':
                return (new SelectType($request, $slug, $row, $options))->handle();
            case 'timestamp':
                return (new TimestampType($request, $slug, $row, $options))->handle();
            default:
                return (new TextType($request, $slug, $row, $options))->handle();
        }
    }

    protected function cleanup($dataType, $data)
    {
        // Delete Images
        $this->deleteBreadImages($data, $dataType->deleteRows->where('type', 'image'));

        // Delete Files
        foreach ($dataType->deleteRows->where('type', 'file') as $row) {
            foreach (json_decode($data->{$row->field}) as $file) {
                $this->deleteFileIfExists($file->download_link);
            }
        }
    }

    protected function getFieldsWithValidationRules($fieldsConfig)
    {
        // $fieldsConfig->filter(function ($value) {
        //     $decoded = json_decode($value->details, true);
        // });

        return $fieldsConfig->filter(function ($value) {
            if (empty($value->details)) {
                return false;
            }
            $decoded = json_decode($value->details, true);

            return ! empty($decoded['validation']['rule']);
        });
    }

}
