<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AttendanceCorrectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 承認待ちの修正申請が全て表示されている
    public function test_application_list_waiting_view()
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
            'date' => $now->addDay()->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $this->from('/attendance/detail/' . $work1->id)->post('/attendance/correction/apply', [
            'work_id' => $work1->id,
            'attendance' => '08:00:00',
            'leaving' => '17:00:00',
            'remarks' => '修正申請テスト',
            'update' => 'pending',
        ])
        ->assertRedirect('/attendance/detail/' . $work1->id);

        $this->from('/attendance/detail/' . $work2->id)->post('/attendance/correction/apply', [
            'work_id' => $work2->id,
            'attendance' => '08:00:00',
            'leaving' => '17:00:00',
            'remarks' => '修正申請テスト2',
            'update' => 'pending',
        ])
        ->assertRedirect('/attendance/detail/' . $work2->id);
        
        $list = $this->get('/stamp_correction_request/list?tab=waiting');
        $list->assertStatus(200);
        $list->assertSee('テスト1');
        $list->assertSee($now->format('Y/m/d'));
        $list->assertSee('修正申請テスト');
        $list->assertSee('テスト2');
        $list->assertSee($now->addDay()->format('Y/m/d'));
        $list->assertSee('修正申請テスト2');
        
        CarbonImmutable::setTestNow();
    }

    // 承認済みの修正申請が全て表示されている
    public function test_application_list_done_view()
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
            'update' => 'done',
            'remarks' => '修正申請テスト',
        ]);

        $work2 = \App\Models\Work::factory()->create([
            'user_id' => $user2->id,
            'date' => $now->addDay()->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'done',
            'remarks' => '修正申請テスト2',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        
        $list = $this->get('/stamp_correction_request/list?tab=done');
        $list->assertStatus(200);
        $list->assertSee('テスト1');
        $list->assertSee($now->format('Y/m/d'));
        $list->assertSee('修正申請テスト');
        $list->assertSee('テスト2');
        $list->assertSee($now->addDay()->format('Y/m/d'));
        $list->assertSee('修正申請テスト2');
        
        CarbonImmutable::setTestNow();
    }

    // 修正申請の詳細内容が正しく表示されている
    public function test_application_detail_view()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'テスト1',
            'email' => 'test1@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $work = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'done',
            'remarks' => '修正申請テスト',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        
        $list = $this->get('/admin/attendance/detail/' . $work->id);
        $list->assertStatus(200);
        $list->assertSee('テスト1');
        $list->assertSee($now->format('Y') . '年');
        $list->assertSee($now->format('n') . '月' . $now->format('j') . '日');
        $list->assertSee('09:00');
        $list->assertSee('18:00');
        $list->assertSee('修正申請テスト');
        
        CarbonImmutable::setTestNow();
    }

    // 修正申請の承認処理が正しく行われる
    public function test_application_view()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'テスト1',
            'email' => 'test1@example.com',
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

        $unapproved = \App\Models\UnapprovedWork::factory()->create([
            'work_id' => $work->id,
            'user_id' => $user->id,
            'date' => $work->date,
            'attendance' => '10:00:00',
            'leaving' => '19:00:00',
            'remarks' => '修正後の備考',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->post('/admin/approve', [
            'work_id' => $work->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('works', [
            'id' => $work->id,
            'attendance' => '10:00:00',
            'leaving' => '19:00:00',
            'remarks' => '修正後の備考',
            'update' => 'done',
        ]);

        $this->assertDatabaseMissing('unapproved_works', [
            'id' => $unapproved->id,
        ]);
        
        CarbonImmutable::setTestNow();
    }
}
