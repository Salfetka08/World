<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domain\EloquentModels\EntertainmentPlace;

class EntertainmentPlaceTest extends TestCase
{
    // Создание модели через фабрику
    public function test_can_create_place()
    {
        $place = EntertainmentPlace::factory()->create();

        $this->assertInstanceOf(EntertainmentPlace::class, $place);
        $this->assertNotNull($place->id);
        $this->assertIsString($place->name);
        $this->assertIsFloat($place->rating);
    }

    // Тест скоупов
    public function test_scope_active_returns_only_active_places()
    {
        EntertainmentPlace::factory()->count(3)->create(['is_active' => true]);
        EntertainmentPlace::factory()->count(2)->create(['is_active' => false]);

        $activePlaces = EntertainmentPlace::active()->get();

        $this->assertCount(3, $activePlaces);
        foreach ($activePlaces as $place) {
            $this->assertTrue($place->is_active);
        }
    }

    // Тест кастов (JSON полей)
    public function test_details_is_cast_to_array()
    {
        $place = EntertainmentPlace::factory()->create([
            'details' => json_encode(['description' => 'Test description'])
        ]);

        $this->assertIsArray($place->details);
        $this->assertEquals('Test description', $place->details['description']);
    }
}
