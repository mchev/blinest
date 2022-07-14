<?php

namespace App\Services;

use Image;

class MosaicGenerator
{
    /**
     * @param $images An array of image urls
     */
    public function generate(array $images, int $width = 300, int $height = 300, int $rows = 3, int $cols = 5)
    {

        // Items sizes
        $itemWidth = $width / $cols;
        $itemHeight = $height / $rows;

        // Offsets
        $offsetY = 0;
        $offsetX = 0;

        // Creating the canva
        $canvas = Image::canvas($width, $height);

        $imagesRows = array_chunk($images, $cols);

        foreach ($imagesRows as $items) {
            foreach ($items as $url) {
                $item = Image::make($url);

                $item->resize(null, $itemHeight, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $item->crop($itemWidth, $itemHeight);

                $canvas->insert($item, 'top-left', $offsetX, $offsetY);

                $offsetX += $itemWidth;
            }

            $offsetY += $itemHeight;
            $offsetX = 0;
        }

        return $canvas;
    }
}
