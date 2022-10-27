<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// WIP

trait Sluggable
{

    /**
     * Boot the sluggable trait for a model.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::saving(function (Model $model) {
            if (empty($model->slug)) {
                $model->slug = $model->slugify($model->name);
            }
        });
    }

    public function slugify($value) {

        if (static::whereSlug($slug = Str::slug($value))->exists()) {

            $slug = $this->incrementSlug($slug);
        }

        return $slug;
    }

    public function incrementSlug($slug) {

        $original = $slug;

        $count = 0;

        while (static::whereSlug($slug)->exists()) {

            $slug = "{$original}-" . $count++;
        }

        return $slug;

    }


}
