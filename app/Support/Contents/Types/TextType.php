<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;

class TextType extends AbstractType
{
    public function handle()
    {
        $value = $this->request->input($this->row->field);

        if (isset($this->options->null)) {
            return $value === $this->options->null ? null : $value;
        }

        return $value;
    }
}
