<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppleMusicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($term)
    {

        dd($term);
        $query = filter_var ( $term, FILTER_SANITIZE_STRING);
        $query = trim ( $query );
        $query = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $query);
        $query = str_replace(' ', '+', $query);

        $url = 'https://itunes.apple.com/search?term=' . $query . '&country=' . config('app.locale') . '&entity=musicTrack&limit=10&output=json';

        try {
            $json = json_decode(file_get_contents($url, false), true);
        }
        catch (Exception $e) {
            return response()->json($e->getMessage());
        }

        return parent::toArray($json);

    }
}
