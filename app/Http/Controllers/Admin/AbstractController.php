<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Contents\Types\TextType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
        foreach ($rows as $row) {
            $options = json_decode($row->details);

            if (! $request->hasFile($row->field)) {
            }

            $content = $this->getContentFromType($request, $slug, $row, $options);


            if (null === $content) {
            }

            if ($row->type === 'relationship' && $options->type === 'belongsToMany') {
            } else {
                $model->{$row->field} = $content;
            }
        }
        $model->save();

        return $model;
    }

    public function getContentFromType(Request $request, $slug, $row, $options)
    {
        switch ($row->type) {
            case 'password':
                break;
            case 'checkbox':
                break;
            case 'file':
                break;
            case 'timestamp':
                break;
            default:
                return (new TextType($request, $slug, $row, $options))->handle();
                break;
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
}
