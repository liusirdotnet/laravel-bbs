<?php

namespace App\Support\Components;

class ButtonComponent extends AbstractComponent
{
    protected $text;

    protected $link;

    protected $style;

    /**
     * @param string $text
     * @param string $link
     * @param string $style
     */
    public function create($text, $link = '#', $style = 'default')
    {
        $this->text = $text;
        $this->link = $link;
        $this->style = $style;
    }

    /**
     * @return string
     */
    public function render()
    {
        return "<a href='{$this->link}' class='btn btn-{$this->style}'>{$this->style}</a>";
    }
}
