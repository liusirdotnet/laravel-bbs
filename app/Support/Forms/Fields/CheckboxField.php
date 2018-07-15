<?php

namespace App\Support\Forms\Fields;

use App\Support\Contracts\Forms\Fields\AbstractField;

class CheckboxField extends AbstractField
{
    /**
     * @var string
     */
    protected $codeName = 'checkbox';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('admin.forms.fields.checkbox', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
