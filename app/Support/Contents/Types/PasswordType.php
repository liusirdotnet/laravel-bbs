<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;

class PasswordType extends AbstractType
{
    public function handle()
    {
        $password = $this->request->input($this->row->field);

        return empty($password) ? null : bcrypt($password);
    }
}
