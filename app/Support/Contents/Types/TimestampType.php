<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;
use Carbon\Carbon;

class TimestampType extends AbstractType
{
    public function handle()
    {
        if (! \in_array($this->request->method(), ['PUT', 'POST'])) {
            return null;
        }

        $content = $this->request->input($this->row->field);

        if (empty($content)) {
            return null;
        }

        return Carbon::parse($content);
    }
}
