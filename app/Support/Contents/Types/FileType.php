<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FileType extends AbstractType
{
    public function handle()
    {
        if (! $this->request->has($this->row->field)) {
            return json_encode([]);
        }

        $files = Arr::wrap($this->request->file($this->row->field));
        $filesPath = [];

        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($files as $file) {
            $filename = $this->generateFileName();
            $path = $this->generatePath();
            $file->storeAs(
                $path,
                $filename . '.' . $file->getClientOriginalExtension(),
                config('voyager.storage.disk', 'public')
            );
            $filesPath[] = [
                'download_link' => $path . $filename . '.' . $file->getClientOriginalExtension(),
                'original_name' => $file->getClientOriginalName(),
            ];
        }

        return json_encode($filesPath);
    }

    /**
     * @return string
     */
    protected function generatePath()
    {
        return $this->slug . DIRECTORY_SEPARATOR . date('FY') . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function generateFileName()
    {
        return Str::random(20);
    }
}
