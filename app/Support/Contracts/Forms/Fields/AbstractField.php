<?php

namespace App\Support\Contracts\Forms\Fields;

use App\Support\Traits\Renderable;

abstract class AbstractField implements FieldInterface
{
    use Renderable;

    public function handle($row, $dataType, $dataTypeContent)
    {
        $content = $this->createContent(
            $row,
            $dataType,
            $dataTypeContent,
            json_decode($row->details)
        );

        return $this->render($content);
    }

    public function supports($driver)
    {
        if (empty($this->supports)) {
            return true;
        }

        return \in_array($driver, $this->supports, true);
    }

    public function getCodename()
    {
        if (empty($this->codeName)) {
            $name = class_basename($this);

            if (ends_with($name, 'Field')) {
                $name = substr($name, 0, -\strlen('Field'));
            }

            $this->codeName = snake_case($name);
        }

        return $this->codeName;
    }

    public function getName()
    {
        if (empty($this->name)) {
            $this->name = ucwords(str_replace('_', ' ', $this->getCodename()));
        }

        return $this->name;
    }
}
