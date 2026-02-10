<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 自分が行った勤怠情報が全て表示されている
    public function test_attendance_list_work_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $work1Date = '2026-02-02';
        $work2Date = '2026-02-03';

        \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $work1Date,
            'attendance' => '01:00:00',
            'leaving' => '09:00:00',
            'update' => 'no',
        ]);

        \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $work2Date,
            'attendance' => '10:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);


        $this->actingAs($user);

        $response = $this->get('/attendance/list?month=2026-02');
        $response->assertStatus(200);
        $response->assertSee('02/02');
        $response->assertSee('02/03');
        $response->assertSee('01:00');
        $response->assertSee('09:00');
        $response->assertSee('10:00');
        $response->assertSee('18:00');
        
        CarbonImmutable::setTestNow();
    }

    // 勤怠一覧画面に遷移した際に現在の月が表示される
    public function test_attendance_list_month_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = CarbonImmutable::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        $this->actingAs($user);

        $response = $this->get('/attendance/list');
        $response->assertStatus(200);
        $response->assertSee($now->format('Y/m'));
    }

    // 「前月」を押下した時に表示月の前月の情報が表示される
    public function test_attendance_list_before_month_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $before_month = $now->subMonth()->setDay(15);

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $before_month->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $link_day_before = Carbon::parse($now)->subMonth()->format('Y-m');

        $response = $this->get('/attendance/list');
        $response->assertStatus(200);
        $response = $this->get('/attendance/list?month=' .  $link_day_before);
        $response->assertSee($now->subMonth()->format('Y/m'));
        $response->assertSee($before_month->format('m/d'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        
        CarbonImmutable::setTestNow();
    }

    // 「翌月」を押下した時に表示月の前月の情報が表示される
    public function test_attendance_list_after_month_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $after_month = $now->addMonth()->setDay(15);

        $works = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $after_month->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $link_day_after = Carbon::parse($now)->addMonth()->format('Y-m');

        $response = $this->get('/attendance/list');
        $response->assertStatus(200);
        $response = $this->get('/attendance/list?month=' .  $link_day_after);
        $response->assertSee($now->addMonth()->format('Y/m'));
        $response->assertSee($after_month->format('m/d'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        
        CarbonImmutable::setTestNow();
    }

    // 「詳細」を押下すると、その日の勤怠詳細画面に遷移する
    public function test_detail_move()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $work = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance/list?month=2026-02');
        $response->assertStatus(200);

        $response = $this->get('/attendance/detail/' . $work->id);
        $response->assertStatus(200);

        $response->assertSee('テスト');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        
        CarbonImmutable::setTestNow();
    }
}
