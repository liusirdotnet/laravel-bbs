<?php

namespace App\Support\Forms\Fields;

use App\Support\Contracts\Forms\Fields\AbstractField;

class TimestampField extends AbstractField
{
    protected $codeName = 'time';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('admin.forms.fields.time', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
