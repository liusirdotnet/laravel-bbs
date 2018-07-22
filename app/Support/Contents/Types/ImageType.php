<?php

namespace App\Support\Contents\Types;

use App\Support\Contracts\Contents\Types\AbstractType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class ImageType extends AbstractType
{
    public function handle()
    {
        if ($this->request->hasFile($this->row->field)) {
            $file = $this->request->file($this->row->field);
            $path = $this->slug . DIRECTORY_SEPARATOR . date('Ym') . DIRECTORY_SEPARATOR;
            $filename = $this->generateFileName($file, $path);

            $image = Image::make($file);
            $fullPath = $path . $filename . '.' . $file->getClientOriginalExtension();

            $resizeWidth = $resizeHeight = null;

            if (isset($this->options->resize)
                && (isset($this->options->resize->width) || isset($this->options->resize->height))
            ) {
                if (isset($this->options->resize->width)) {
                    $resizeWidth = $this->options->resize->width;
                }

                if (isset($this->options->resize->height)) {
                    $resizeHeight = $this->options->resize->height;
                }
            } else {
                $resizeWidth = $image->width();
                $resizeHeight = $image->height();
            }

            $quality = isset($this->options->quality) ? (int) $this->options->quality : 75;
            $image = $image->resize($resizeWidth, $resizeHeight, function (Constraint $constraint) {
                $constraint->aspectRatio();

                if (isset($this->options->upsize) && ! $this->options->upsize) {
                    $constraint->upsize();
                }
            })->encode($file->getClientOriginalExtension(), $quality);

            $disk = config('admin.storage.disk');
            if ($this->isAnimatedGif($file)) {
                Storage::disk($disk)->put($fullPath, file_get_contents($file), 'public');
                Storage::disk($disk)->put(
                    $path . $filename . '-static.' . $file->getClientOriginalExtension(),
                    (string) $image,
                    'public'
                );
            } else {
                Storage::disk($disk)->put($fullPath, (string) $image, 'public');
            }

            if (isset($this->options->thumbnails)) {
                foreach ($this->options->thumbnails as $thumbnail) {
                    if (isset($thumbnail->name, $thumbnail->scale)) {
                        $scale = $thumbnail->scale / 100;
                        $thumbResizeWith = $resizeWidth;
                        $thumbResizeHeight = $resizeHeight;

                        if ($thumbResizeWith !== null && $thumbResizeHeight !== 'null') {
                            $thumbResizeWith = (int) $thumbResizeWith * $scale;
                        }

                        if ($thumbResizeHeight !== null && $thumbResizeHeight !== 'null') {
                            $thumbResizeHeight = (int) $thumbResizeHeight * $scale;
                        }

                        $image = Image::make($file)->resize(
                            $thumbResizeWith,
                            $thumbResizeHeight,
                            function (Constraint $constraint) {
                                $constraint->aspectRatio();

                                if (isset($this->options->upsize) && ! $this->options->upsize) {
                                    $constraint->upsize();
                                }
                            }
                        )->encode($file->getClientOriginalExtension(), $quality);
                    } elseif (isset($thumbnail->crop->width, $thumbnail->crop->height)) {
                        $cropWidth = $thumbnail->crop->width;
                        $cropHeight = $thumbnail->crop->height;
                        $image = Image::make($file)->fit($cropWidth, $cropHeight)
                            ->encode($file->getClientOriginalExtension(), $quality);
                    }

                    $filepath = $path . $filename . '-' . $thumbnail->name . '' . $file->getClientOriginalExtension();
                    Storage::disk($disk)->put($filepath, (string) $image, 'public');
                }
            }

            return $fullPath;
        }
    }

    protected function generateFileName(UploadedFile $file, $path)
    {
        if (isset($this->options->preserveFileUploadName) && $this->options->preserveFileUploadName) {
            $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
            $counter = 1;

            // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
            $storage = Storage::disk(config('admin.storage.disk'))
                ->exists($path . $filename . '.' . $file->getClientOriginalExtension());
            while ($storage) {
                $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension())
                    . ($counter++);
            }
        } else {
            $filename = Str::random(20);

            // Make sure the filename does not exist, if it does, just regenerate
            $storage = Storage::disk(config('admin.storage.disk'))
                ->exists($path . $filename . '.' . $file->getClientOriginalExtension());
            while ($storage) {
                $filename = Str::random(20);
            }
        }

        return $filename;
    }

    protected function isAnimatedGif($filename)
    {
        $raw = file_get_contents($filename);

        $offset = 0;
        $frames = 0;
        while ($frames < 2) {
            $where1 = strpos($raw, "\x00\x21\xF9\x04", $offset);
            if ($where1 === false) {
                break;
            } else {
                $offset = $where1 + 1;
                $where2 = strpos($raw, "\x00\x2C", $offset);
                if ($where2 === false) {
                    break;
                } else {
                    if ($where1 + 8 === $where2) {
                        $frames++;
                    }
                    $offset = $where2 + 1;
                }
            }
        }

        return $frames > 1;
    }
}
