<?php

namespace App\Support\Forms\Fields;

use App\Support\Contracts\Forms\Fields\AbstractField;

class HiddenField extends AbstractField
{
    protected $codename = 'hidden';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('admin.forms.fields.hidden', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
