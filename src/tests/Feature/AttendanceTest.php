<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 出勤ボタンが正しく機能する
    public function test_button_working()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('出勤');

        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $response = $this->post('/stamp/attendance');

        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $response->assertSee('出勤中');
    }

    // 出勤は一日一回のみできる
    public function test_working_once_par_day()
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

        $response->assertDontSee('<form action="/stamp/attendance"', false);
    }

    // 出勤時刻が勤怠一覧画面で確認できる
    public function test_attendance_list_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $this->actingAs($user);

        $now = new CarbonImmutable('2026-02-02T00:00:00+09:00');
        CarbonImmutable::setTestNow($now);
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $response = $this->post('/stamp/attendance');

        $date_carbon = Carbon::parse($date);
        $time_carbon = Carbon::parse($time);
        
        $response = $this->get('/attendance/list?month=2026-02');
        $response->assertSee($now->format('m/d'));
        $response->assertSee($now->format('H:i'));

        CarbonImmutable::setTestNow();
    }
}
