<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Image;

trait HasPicture
{
    /**
     * Update the user's profile photo.
     *
     * @return void
     */
    public function updatePhoto(UploadedFile $photo, int $width = 350, int $height = 350)
    {
        tap($this->photo_path, function ($previous) use ($photo, $width, $height) {
            $image = Image::make($photo->getRealPath())->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp')->stream();

            $filename = uniqid().'.webp';

            Storage::disk($this->profilePhotoDisk())->put($this->getTable().'/'.$filename, $image);

            $this->forceFill([
                'photo_path' => $this->getTable().'/'.$filename,
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deletePhoto()
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
     *
     * @return string
     */
    protected function defaultPhotoUrl()
    {
        if ($this->getTable() === 'rooms') {
            // $default = $this->playlists()?->first()?->tracks()?->latest()->first()?->artwork_url;
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
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return config('filesystems.default');
    }
}
