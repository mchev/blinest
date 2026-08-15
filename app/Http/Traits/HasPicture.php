<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait HasPicture
{
    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(UploadedFile $photo, int $width = 350, int $height = 350): void
    {
        tap($this->photo_path, function ($previous) use ($photo, $width, $height) {
            $filename = uniqid().'.webp';
            $directory = $this->getTable();
            $disk = $this->profilePhotoDisk();

            Image::fromUpload($photo)
                ->contain($width, $height)
                ->toWebp()
                ->storeAs($directory, $filename, $disk);

            $this->forceFill([
                'photo_path' => $directory.'/'.$filename,
            ])->save();

            if ($previous) {
                Storage::disk($disk)->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(): void
    {
        if (is_null($this->photo_path)) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->photo_path);

        $this->forceFill([
            'photo_path' => null,
        ])->save();
    }

    /**
     * Get the photo.
     */
    public function photo(): Attribute
    {
        return Attribute::get(function () {
            return $this->photo_path
                    ? Storage::disk($this->profilePhotoDisk())->url($this->photo_path)
                    : $this->defaultPhotoUrl();
        });
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    protected function defaultPhotoUrl(): string
    {
        if ($this->getTable() === 'rooms') {
            $default = 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=random&color=fff&size=300@format=svg&length=5&font-size=0.15';
        } else {
            $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
                return mb_substr($segment, 0, 1);
            })->join(' '));

            $default = 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=1f2937&size=300';
        }

        return $default;
    }

    /**
     * Get the disk that profile photos should be stored on.
     */
    protected function profilePhotoDisk(): string
    {
        return config('filesystems.default');
    }
}
