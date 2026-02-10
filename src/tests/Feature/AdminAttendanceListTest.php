<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Models\Admin;  

class AdminAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // その日になされた全ユーザーの勤怠情報が正確に確認できる
    public function test_attendance_list_user_view()
    {
        $user1 = \App\Models\User::factory()->create([
            'name' => 'テスト1',
            'email' => 'test1@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'テスト2',
            'email' => 'test2@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $work1 = \App\Models\Work::factory()->create([
            'user_id' => $user1->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $work2 = \App\Models\Work::factory()->create([
            'user_id' => $user2->id,
            'date' => $now->toDateString(),
            'attendance' => '08:00:00',
            'leaving' => '17:00:00',
            'update' => 'no',
        ]);


        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        
        
        $list = $this->get('/admin/attendance/list?day=' . $now->format('Y-m-d'));
        $list->assertStatus(200);
        $list->assertSee('テスト1');
        $list->assertSee('09:00');
        $list->assertSee('18:00');
        $list->assertSee('テスト2');
        $list->assertSee('08:00');
        $list->assertSee('17:00');

        CarbonImmutable::setTestNow();
    }

    // 「遷移した際に現在の日付が表示される
    public function test_attendance_list_now_date_view()
    {
        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $list = $this->get('/admin/attendance/list');
        $list->assertStatus(200);
        $list->assertSee($now->format('Y/m/d'));

        CarbonImmutable::setTestNow();
    }

        // 「前日」を押下した時に前の日の勤怠情報が表示される
        public function test_attendance_list_before_day_view()
        {
            $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
            CarbonImmutable::setTestNow($now);

            $admin = \App\Models\Admin::factory()->create();
            $this->actingAs($admin, 'admin');

            $beforeDay = $now->subDay()->toDateString();
            $today = $now->toDateString();

            $user = \App\Models\User::factory()->create();

            \App\Models\Work::factory()->create([
                'user_id' => $user->id,
                'date' => $beforeDay,
                'attendance' => '08:00:00',
                'leaving' => '17:00:00',
            ]);

            \App\Models\Work::factory()->create([
                'user_id' => $user->id,
                'date' => $today,
                'attendance' => '09:00:00',
                'leaving' => '18:00:00',
            ]);

            $response = $this->get('/admin/attendance/list?day=' . $beforeDay);
            $response->assertStatus(200);

            $response->assertSee($now->subDay()->format('Y/m/d'));
            $response->assertSee('08:00');
            $response->assertSee('17:00');
            $response->assertDontSee('09:00');
            $response->assertDontSee('18:00');

            CarbonImmutable::setTestNow();
        }

    // 「「翌日」を押下した時に次の日の勤怠情報が表示される
    public function test_attendance_list_after_day_view()
    {
        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $afterDay = $now->addDay()->toDateString();
        $today    = $now->toDateString();

        $user = \App\Models\User::factory()->create();

        \App\Models\Work::factory()->create([
            'user_id'    => $user->id,
            'date'       => $afterDay,
            'attendance' => '10:00:00',
            'leaving'    => '19:00:00',
        ]);

        \App\Models\Work::factory()->create([
            'user_id'    => $user->id,
            'date'       => $today,
            'attendance' => '09:00:00',
            'leaving'    => '18:00:00',
        ]);

        $response = $this->get('/admin/attendance/list?day=' . $afterDay);
        $response->assertStatus(200);

        $response->assertSee($now->addDay()->format('Y/m/d'));
        $response->assertSee('10:00');
        $response->assertSee('19:00');
        $response->assertDontSee('09:00');
        $response->assertDontSee('18:00');

        CarbonImmutable::setTestNow();
    }
}
