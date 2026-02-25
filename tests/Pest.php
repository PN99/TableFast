<?php

use App\Models\OpeningHour;
use App\Models\RestaurantSetting;
use App\Models\Table;
use App\Models\Zone;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

/**
 * Seed a minimal "open restaurant" for tests that need full environment.
 *
 * Returns: ['settings' => RestaurantSetting, 'zone' => Zone, 'tables' => Table[]]
 */
function seedOpenRestaurant(): array
{
    $settings = RestaurantSetting::create([
        'name'                        => 'Test Restaurant',
        'contact_email'               => 'info@test.cz',
        'contact_phone'               => '+420123456789',
        'auto_confirm_reservations'   => false,
        'max_guests'                  => 20,
        'max_duration_minutes'        => 240,
        'confirmation_timeout_minutes' => 30,
        'reservation_cutoff_hours'    => 2,
        'min_advance_hours'           => 0,
    ]);

    // Opening hours: Mon-Sat open 10:00-22:00, Sun closed
    for ($day = 1; $day <= 6; $day++) {
        OpeningHour::factory()->day($day)->create();
    }
    OpeningHour::factory()->day(7)->closed()->create();

    // Default zone with 2 tables (capacity 4 each = 8 total)
    $zone = Zone::factory()->default()->create(['name' => 'Hlavní sál']);

    $tables = [
        Table::factory()->create(['zone_id' => $zone->id, 'name' => 'Stůl A', 'capacity' => 4]),
        Table::factory()->create(['zone_id' => $zone->id, 'name' => 'Stůl B', 'capacity' => 4]),
    ];

    return compact('settings', 'zone', 'tables');
}
