<?php

namespace App\Handlers;

use Illuminate\Http\UploadedFile;

class ImageHandler
{
    /**
     * @var array
     */
    protected static $allowedExt = ['png', 'jpg', 'gif', 'jpeg'];

    /**
     * 上传图片。
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string                        $folder
     * @param string                        $prefix
     *
     * @return array|bool
     */
    public function upload(UploadedFile $file, $folder, $prefix)
    {
        $folder = 'uploads/images/' . $folder . '/' . date('Ym/d');
        $ext = strtolower($file->getClientOriginalExtension()) ?: 'png';
        $filename = $prefix . '_' . time() . '_' . str_random(10) . '.' . $ext;

        if (! \in_array($ext, self::$allowedExt, true)) {
            return false;
        }

        $file->move($folder, $filename);

        return [
            'path' => config('app.url') . "/$folder/$filename",
        ];
    }
}
