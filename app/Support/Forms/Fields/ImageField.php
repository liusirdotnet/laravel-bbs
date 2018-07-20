<?php

namespace App\Support\Forms\Fields;

use App\Support\Contracts\Forms\Fields\AbstractField;

class ImageField extends AbstractField
{
    protected $codename = 'image';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('admin.forms.fields.image', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
