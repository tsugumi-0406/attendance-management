<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 勤務外の場合、勤怠ステータスが正しく表示される
    public function test_status_off_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $response->assertSee('勤務外');
    }

    // 出勤中の場合、勤怠ステータスが正しく表示される
    public function test_status_working_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'attendance' => $time,
            'leaving' => null,
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $response->assertSee('出勤中');
    }

    // 休憩中の場合、勤怠ステータスが正しく表示される
    public function test_status_breaking_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'attendance' => $time,
            'leaving' => null,
            'update' => 'no',
        ]);

        $breaks = \App\Models\BreakTime::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'start' => $time,
            'stop' => null,
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $response->assertSee('休憩中');
    }

    // 退勤済の場合、勤怠ステータスが正しく表示される
    public function test_status_leaving_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'attendance' => $time,
            'leaving' => $time,
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $response->assertSee('退勤済');
    }
}
