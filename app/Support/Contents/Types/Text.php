<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;

class Text extends AbstractType
{
    public function handle()
    {
        $text = $this->request->input($this->row->field);

        if (isset($this->options->null)) {
            return $text === $this->options->null ?: $text;
        }

        return $text;
    }
}
