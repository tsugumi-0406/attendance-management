<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class BreakTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 休憩ボタンが正しく機能する
    public function test_button_breaking_start()
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
        $response->assertSee('休憩入');

        $this->from('/attendance')->post('/stamp/break/start');

        $this->assertDatabaseHas('break_times', [
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'start' => $now->format('H:i:s'),
            'stop' => null,
            'update' => 'no',
        ]);

        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩中');
    }

    // 休憩は一日に何回でもできる
    public function test_breaking_start_repeat()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = CarbonImmutable::now();

        \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => null,
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $this->from('/attendance')->post('/stamp/break/start');
        $this->from('/attendance')->post('/stamp/break/stop');
        
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩入');
    }

    // 休憩戻ボタンが正しく機能する
    public function test_breaking_button_stop()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = CarbonImmutable::now();

        \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => null,
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $this->from('/attendance')->post('/stamp/break/start');
        
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩戻');

        $this->from('/attendance')->post('/stamp/break/stop');

        $this->assertDatabaseMissing('break_times', [
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'stop' => null,
        ]);

        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    // 休憩戻は一日に何回でもできる
    public function test_breaking_stop_repeat()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = CarbonImmutable::now();

        \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => null,
            'update' => 'no',
        ]);

        $this->actingAs($user);
        
        $this->from('/attendance')->post('/stamp/break/start');
        $this->from('/attendance')->post('/stamp/break/stop');
        $this->from('/attendance')->post('/stamp/break/start');

        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩戻');
    }

    // 休憩時刻が勤怠一覧画面で確認できる
    public function test_breaking_list_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);
        $this->actingAs($user);

        $now_attendance = new CarbonImmutable('2026-02-02T00:00:00+09:00');
        CarbonImmutable::setTestNow($now_attendance);

        \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now_attendance->toDateString(),
            'attendance' => $now_attendance->format('H:i:s'),
            'leaving' => null,
            'update' => 'no',
        ]);

        $now_start = new CarbonImmutable('2026-02-02T01:00:00+09:00');
        CarbonImmutable::setTestNow($now_start);
        $this->from('/attendance')->post('/stamp/break/start');

        $now_stop = new CarbonImmutable('2026-02-02T02:00:00+09:00');
        CarbonImmutable::setTestNow($now_stop);
        $this->from('/attendance')->post('/stamp/break/stop');

        $response = $this->get('/attendance/list?month=2026-02');
        $response->assertStatus(200);
        $response->assertSee('02/02');
        $response->assertSee('1:00');
        
        CarbonImmutable::setTestNow();
    }
}
