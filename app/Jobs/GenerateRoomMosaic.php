<?php

namespace App\Jobs;

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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
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
            ->take(15)
            ->distinct()
            ->pluck('artwork_url')
            ->toArray();

        $image = (new MosaicGenerator())->generate($tracks);

        $image->save(public_path('/images/rooms/'.$this->room->id.'.jpg'), 100);
    }
}
