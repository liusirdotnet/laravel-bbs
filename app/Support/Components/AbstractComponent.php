<?php

namespace App\Support\Components;

use App\Support\Alert;
use App\Support\Contracts\ComponentInterface;

abstract class AbstractComponent implements ComponentInterface
{
    protected $alert;

    /**
     * @param \App\Support\Alert $alert
     *
     * @return $this
     */
    public function setAlert(Alert $alert)
    {
        $this->alert = $alert;

        return $this;
    }

    public function __call($name, $arguments)
    {
        return \call_user_func_array([$this->alert, $name], $arguments);
    }
}
