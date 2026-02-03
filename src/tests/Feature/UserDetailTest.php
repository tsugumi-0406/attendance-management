<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class UserDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */


    // 「勤怠詳細画面の「名前」がログインユーザーの氏名になっている
    public function test_detail_name_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/detail/' . $works->id);
        $response->assertStatus(200);
        $response->assertSee($now->format('Y年'));
        $response->assertSee($now->format('n月j日'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        
        Carbon::setTestNow();
    }

    // 「勤怠詳細画面の「日付」が選択した日付になっている
    public function test_detail_date_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/detail/' . $works->id);
        $response->assertStatus(200);
        $response->assertSee($now->format('Y年'));
        $response->assertSee($now->format('n月j日'));
        
        Carbon::setTestNow();
    }

    // 「出勤・退勤」にて記されている時間がログインユーザーの打刻と一致している
    public function test_detail_attendance_leaving_time_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/detail/' . $works->id);
        $response->assertStatus(200);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        
        CarbonImmutable::setTestNow();
    }

    // 「休憩」にて記されている時間がログインユーザーの打刻と一致している
    public function test_detail_break_time_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $breaks = \App\Models\BreakTime::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'start' => '13:00:00',
            'stop' => '14:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/detail/' . $works->id);
        $response->assertStatus(200);
        $response->assertSee('13:00');
        $response->assertSee('14:00');
        
        CarbonImmutable::setTestNow();
    }
}
