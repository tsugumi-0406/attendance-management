<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Models\Admin;  

class UserDetailApplicationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 「出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
    public function test_application_attendance_time_validation()
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

        $response = $this->from('/attendance/detail/' . $works->id)->post('/attendance/correction/apply', [
            'work_id' => $works->id,
            'attendance' => '19:00:00',
            'leaving' => '18:00:00',
        ]);
        
        $response->assertSessionHasErrors([
            'attendance' => '出勤時間が不適切な値です',
        ]);
        
        CarbonImmutable::setTestNow();
    }

    // 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示される
    public function test_application_start_time_validation()
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

        $response = $this->from('/attendance/detail/' . $works->id)->post('/attendance/correction/apply', [
            'work_id' => $works->id,
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'remarks' => 'テスト',
            'break_requests' => [
                [
                    'start' => '19:00:00', 
                    'stop'  => '14:00:00',
                ],
            ],
        ]);

        $response->assertSessionHasErrors([
            'break_requests.0.start' => '休憩時間が不適切な値です',
        ]);
        
        CarbonImmutable::setTestNow();
    }

    // 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
    public function test_application_stop_time_validation()
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

        $response = $this->from('/attendance/detail/' . $works->id)->post('/attendance/correction/apply', [
            'work_id' => $works->id,
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'remarks' => 'テスト',
            'break_requests' => [
                [
                    'start' => '13:00:00', 
                    'stop'  => '19:00:00',
                ],
            ],
        ]);

        $response->assertSessionHasErrors([
            'break_requests.0.stop' => '休憩時間もしくは退勤時間が不適切な値です',
        ]);
        
        CarbonImmutable::setTestNow();
    }

    // 備考欄が未入力の場合のエラーメッセージが表示される
    public function test_application_remarks_validation()
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

        $response = $this->from('/attendance/detail/' . $works->id)->post('/attendance/correction/apply', [
            'work_id' => $works->id,
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'remarks' => '',
        ]);

        $response->assertSessionHasErrors([
            'remarks' => '備考を記入してください',
        ]);
        
        CarbonImmutable::setTestNow();
    }


    // 修正申請処理が実行される
    public function test_application_correction()
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

        $break = \App\Models\BreakTime::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'start' => '13:00:00',
            'stop' => '14:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $this->from('/attendance/detail/' . $works->id)->post('/attendance/correction/apply', [
            'work_id' => $works->id,
            'attendance' => '08:00:00',
            'leaving' => '17:00:00',
            'remarks' => '修正申請テスト',
            'break_requests' => [
                [
                    'break_id' => $break->id,
                    'start' => '12:30:00',
                    'stop'  => '13:30:00',
                ],
            ],
            'update' => 'pending',
        ])
        ->assertRedirect('/attendance/detail/' . $works->id);

        $unapprovedWork = \App\Models\UnapprovedWork::where('work_id', $works->id)->firstOrFail();
        $unapprovedBreak = \App\Models\UnapprovedBreak::where('break_id', $break->id)->firstOrFail();

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $this->from('/stamp_correction_request/approve/' . $works->id)->post('/admin/approve', [
            'work_id' => $works->id,     
            'attendance' => $unapprovedWork->attendance,
            'leaving' => $unapprovedWork->leaving,
            'remarks' => $unapprovedWork->remarks,
            'break_requests' => [
                [
                    'break_id' => $break->id,
                    'start' => $unapprovedBreak->start,
                    'stop'  => $unapprovedBreak->stop,
                ],
            ],
            'update' => 'done'
        ])
        ->assertRedirect('/admin/stamp_correction_request/approve/' . $works->id);
        
        $list = $this->get('/admin/stamp_correction_request/list?tab=done');
        $list->assertStatus(200);
        $list->assertSee($now->format('Y/m/d'));
        $list->assertSee('修正申請テスト');
        
        CarbonImmutable::setTestNow();
    }

    // 「承認待ち」にログインユーザーが行った申請が全て表示されていること
    public function test_application_list_waiting_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $work1 = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $work2 = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->addDay()->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

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
        $list->assertSee($now->format('Y/m/d'));
        $list->assertSee('修正申請テスト');
        $list->assertSee($now->addDay()->format('Y/m/d'));
        $list->assertSee('修正申請テスト2');
        
        CarbonImmutable::setTestNow();
    }

    // 「承認済み」に管理者が承認した修正申請が全て表示されている
    public function test_application_list_done_view()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $work1 = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $work2 = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->addDay()->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

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

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $unapprovedWork1 = \App\Models\UnapprovedWork::where('work_id', $work1->id)->firstOrFail();
        $unapprovedWork2 = \App\Models\UnapprovedWork::where('work_id', $work2->id)->firstOrFail();

        $this->from('/stamp_correction_request/approve/' . $work1->id)->post('/admin/approve', [
            'work_id' => $work1->id,     
            'attendance' => $unapprovedWork1->attendance,
            'leaving' => $unapprovedWork1->leaving,
            'remarks' => $unapprovedWork1->remarks,
            'update' => 'done'
        ])
        ->assertRedirect('/admin/stamp_correction_request/approve/' . $work1->id);

        $this->from('/stamp_correction_request/approve/' . $work2->id)->post('/admin/approve', [
            'work_id' => $work2->id,     
            'attendance' => $unapprovedWork2->attendance,
            'leaving' => $unapprovedWork2->leaving,
            'remarks' => $unapprovedWork2->remarks,
            'update' => 'done'
        ])
        ->assertRedirect('/admin/stamp_correction_request/approve/' . $work2->id);
        
        $this->actingAs($user);
        $list = $this->get('/stamp_correction_request/list?tab=done');
        $list->assertStatus(200);
        $list->assertSee($now->format('Y/m/d'));
        $list->assertSee('修正申請テスト');
        $list->assertSee($now->addDay()->format('Y/m/d'));
        $list->assertSee('修正申請テスト2');
        
        CarbonImmutable::setTestNow();
    }

    // 各申請の「詳細」を押下すると勤怠詳細画面に遷移する
    public function test_application_list_move_detail()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $now = new CarbonImmutable('2026-02-02T09:00:00+09:00');
        CarbonImmutable::setTestNow($now);

        $work1 = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $this->actingAs($user);

        $this->from('/attendance/detail/' . $work1->id)->post('/attendance/correction/apply', [
            'work_id' => $work1->id,
            'attendance' => '08:00:00',
            'leaving' => '17:00:00',
            'remarks' => '修正申請テスト',
            'update' => 'pending',
        ])
        ->assertRedirect('/attendance/detail/' . $work1->id);
        
        $list = $this->get('/stamp_correction_request/list?tab=waiting');
        $list->assertStatus(200);
        $list->assertSee('/attendance/detail/' . $work1->id);
        $detail = $this->get('/attendance/detail/' . $work1->id);
        $detail->assertStatus(200);
        
        CarbonImmutable::setTestNow();
    }
}
