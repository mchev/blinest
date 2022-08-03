<?php

namespace App\Jobs;

use App\Events\MosaicGenerated;
use App\Models\Room;
use App\Services\MosaicGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateRoomMosaic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $room;

    public $number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Room $room, int $number = 10)
    {
        $this->room = $room;
        $this->number = $number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tracks = $this->room
            ->tracks()
            ->inRandomOrder()
            ->take($this->number)
            ->distinct()
            ->pluck('artwork_url')
            ->toArray();

        $image = (new MosaicGenerator())->generate($tracks);

        $image->save(public_path('/images/rooms/'.$this->room->id.'.webp'), 100);

        $this->room->update([
            'mosaic_path' => '/images/rooms/'.$this->room->id.'.webp',
        ]);

        broadcast(new MosaicGenerated($this->room));
    }
}
