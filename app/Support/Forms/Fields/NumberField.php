<?php

namespace App\Support\Forms\Fields;

use App\Support\Contracts\Forms\Fields\AbstractField;

class NumberField extends AbstractField
{
    protected $codename = 'number';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('admin.forms.fields.number', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
