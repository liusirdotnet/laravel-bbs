<?php

namespace App\Support\Traits;

use Illuminate\View\View;

trait Renderable
{
    public function render($view)
    {
        if ($view instanceof View) {
            return $view;
        }

        return $view;
    }
}
