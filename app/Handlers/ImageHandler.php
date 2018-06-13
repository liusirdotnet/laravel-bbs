<?php

namespace App\Handlers;

use Illuminate\Http\UploadedFile;
use Image;

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
     * @param mixed                         $maxWidth
     *
     * @return array|bool
     */
    public function upload(UploadedFile $file, $folder, $prefix, $maxWidth = false)
    {
        $folder = 'uploads/images/' . $folder . '/' . date('Ym/d');
        $ext = strtolower($file->getClientOriginalExtension()) ?: 'png';
        $filename = $prefix . '_' . time() . '_' . str_random(10) . '.' . $ext;

        if (! \in_array($ext, self::$allowedExt, true)) {
            return false;
        }

        $file->move($folder, $filename);

        if ($maxWidth && $ext !== 'gif') {
            $this->reduceSize("$folder/$filename", $maxWidth);
        }

        return [
            'path' => config('app.url') . "/$folder/$filename",
        ];
    }

    /**
     * 缩小尺寸。
     *
     * @param string $path
     * @param int    $width
     */
    public function reduceSize($path, $width)
    {
        $image = Image::make($path);
        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save();
    }
}
