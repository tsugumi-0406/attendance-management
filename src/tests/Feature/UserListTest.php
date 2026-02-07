<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class UserListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 管理者ユーザーが全一般ユーザーの「氏名」「メールアドレス」を確認できる
    public function test_user_list_view()
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

        $user3 = \App\Models\User::factory()->create([
            'name' => 'テスト3',
            'email' => 'test3@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('admin/staff/list');
        $response->assertStatus(200);
        $response->assertSee($user1->name);
        $response->assertSee($user1->email);
        $response->assertSee($user2->name);
        $response->assertSee($user2->email);
        $response->assertSee($user3->name);
        $response->assertSee($user3->email);
    }

    // ユーザーの勤怠情報が正しく表示される
    public function test_user_attendance_view()
    {
        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $user = \App\Models\User::factory()->create([
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $work = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/attendance/staff/' . $user->id);
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee(Carbon::parse($work->date)->format('m/d'));
        $response->assertSee(Carbon::parse($work->attendance)->format('H:i'));
        $response->assertSee(Carbon::parse($work->leaving)->format('H:i'));
    
        CarbonImmutable::setTestNow();
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

        $work = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $before_month->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $link_day_before = Carbon::parse($now)->subMonth()->format('Y-m');

        $response = $this->get('/admin/attendance/staff/' . $user->id);
        $response->assertStatus(200);
        $response = $this->get('/admin/attendance/staff/' . $user->id . '?month=' .  $link_day_before);
        $response->assertSee($now->subMonth()->format('Y/m'));
        $response->assertSee($before_month->format('m/d'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        
        CarbonImmutable::setTestNow();
    }

    // 「翌月」を押下した時に表示月の翌月の情報が表示される
    public function test_attendance_list_after_month_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $after_month = $now->addMonth()->setDay(15);

        $work = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $after_month->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $link_day_after = Carbon::parse($now)->addMonth()->format('Y-m');

        $response = $this->get('/admin/attendance/staff/' . $user->id);
        $response->assertStatus(200);
        $response = $this->get('/admin/attendance/staff/' . $user->id . '?month=' .  $link_day_after);
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

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/attendance/staff/' . $user->id);
        $response->assertStatus(200);
        $response = $this->get("/admin/attendance/{$work->id}");
        $response->assertStatus(200);
        $response->assertSee('テスト');
        
        CarbonImmutable::setTestNow();
    }
}
