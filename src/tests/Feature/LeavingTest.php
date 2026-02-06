<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class LeavingTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */


    // 退勤ボタンが正しく機能する
    public function test_button_leaving()
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
        $response->assertSee('退勤');

        $response = $this->post('/stamp/leave');

        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $response->assertSee('退勤済');
    }

    // 退勤時刻が勤怠一覧画面で確認できる
    public function test_leaving_list_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $this->actingAs($user);

        $now_attendance = new CarbonImmutable('2026-02-02T00:00:00+09:00');
        CarbonImmutable::setTestNow($now_attendance);
        $date = $now_attendance->toDateString();
        $time = $now_attendance->toTimeString();
        $response = $this->post('/stamp/attendance');

        $now_leaving = new CarbonImmutable('2026-02-02T01:00:00+09:00');
        CarbonImmutable::setTestNow($now_leaving);
        $date = $now_leaving->toDateString();
        $time = $now_leaving->toTimeString();
        $response = $this->post('/stamp/leave');
        
        $response = $this->get('/attendance/list?month=2026-02');
        $response->assertSee($now_leaving->format('m/d'));
        $response->assertSee($now_leaving->format('H:i'));

        CarbonImmutable::setTestNow();
    }
}
