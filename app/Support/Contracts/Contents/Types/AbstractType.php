<?php

namespace App\Support\Contracts\Contents\Types;

use Illuminate\Http\Request;

abstract class AbstractType
{
    protected $request;

    protected $slug;

    protected $row;

    protected $options;

    public function __construct(Request $request, $slug, $row, $options)
    {
        $this->request = $request;
        $this->slug = $slug;
        $this->row = $row;
        $this->options = $options;
    }

    abstract public function handle();
}
