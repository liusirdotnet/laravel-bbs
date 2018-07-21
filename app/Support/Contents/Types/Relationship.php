<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;

class Relationship extends AbstractType
{
    public function handle()
    {
        return $this->request->input($this->row->field);
    }
}
