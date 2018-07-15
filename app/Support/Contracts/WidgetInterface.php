<?php

namespace App\Support\Contracts;

interface WidgetInterface
{
    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed();
}
