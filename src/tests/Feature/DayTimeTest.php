<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class DayTimeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // 現在の日時情報がUIと同じ形式で出力されている
    public function test_day_time_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $now = CarbonImmutable::now();

        $response->assertSee($now->year . '年' . $now->month . '月' . $now->day . '日');
        $response->assertSee($now->format('H:i'));
    }
}
