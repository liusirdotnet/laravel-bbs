<?php

namespace App\Support\Contracts\Forms\Fields;

interface FieldInterface
{
    public function handle($row, $dataType, $dataTypeContent);

    public function createContent($row, $dataType, $dataTypeContent, $options);

    public function supports($driver);

    public function getCodeName();

    public function getName();
}
