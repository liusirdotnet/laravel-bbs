<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;

class CheckboxType extends AbstractType
{
    public function handle()
    {
        return (int) $this->request->input($this->row->field) === 'on';
    }
}
