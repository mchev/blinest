<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use App\Services\Rooms\RoomParticipationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomParticipationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_grant_and_check_room_access_is_scoped_per_user(): void
    {
        $owner = User::factory()->create();
        $room = Room::factory()->create([
            'user_id' => $owner->id,
            'category_id' => Category::factory()->create()->id,
            'is_featured' => false,
            'deleted_at' => null,
        ]);

        $participant = User::factory()->create();
        $outsider = User::factory()->create();
        $service = app(RoomParticipationService::class);

        $this->actingAs($participant);
        $service->grantAccess($room, $participant);

        $this->assertTrue($service->hasAccess($room, $participant));
        $this->assertFalse($service->hasAccess($room, $outsider));
    }
}
