<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;

class SelectType extends AbstractType
{
    public function handle()
    {
        $select = $this->request->input($this->row->field, []);

        if (true === empty($select)) {
            return json_encode([]);
        }

        // Check if we need to parse the editablePivotFields to update fields in the corresponding pivot table
        if (isset($this->options->relationship) && ! empty($this->options->relationship->editablePivotFields)) {
            $pivotContents = [];
            // Read all values for fields in pivot tables from the request
            foreach ($this->options->relationship->editablePivotFields as $pivotField) {
                if (! isset($pivotContent[$pivotField])) {
                    $pivotContent[$pivotField] = [];
                }
                $pivotContents[$pivotField] = $this->request->input('pivot_' . $pivotField);
            }
            // Create a new content array for updating pivot table
            $newContent = [];
            foreach ($select as $contentIndex => $contentValue) {
                $newContent[$contentValue] = [];
                foreach ($pivotContents as $pivotContentKey => $value) {
                    $newContent[$contentValue][$pivotContentKey] = $value[$contentIndex];
                }
            }
            $select = $newContent;
        }

        return json_encode($select);
    }
}
