<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Room;

class SitemapController extends Controller
{
    public function index()
    {
        return response()
            ->view('sitemap', [
                'publicRooms' => Room::isPublic()
                    ->whereNull('deleted_at')
                    ->withCount('rounds')
                    ->orderByDesc('rounds_count')
                    ->get()
                    ->map(fn ($room) => (object) [
                        'url' => url('/rooms/'.$room->slug),
                        'updated_at' => $room->updated_at,
                    ]),
                'pages' => Page::select('id', 'title', 'slug', 'revised_at', 'updated_at')
                    ->orderByDesc('revised_at')
                    ->get()
                    ->unique('slug'),
            ])
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
