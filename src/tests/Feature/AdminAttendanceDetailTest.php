<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class AdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 勤怠詳細画面に表示されるデータが選択したものになっている
    public function test_detail_data_view()
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

        $response = $this->get('/admin/attendance/' . $work->id);
        $response->assertSee($user->name);
        $response->assertSee(Carbon::parse($work->date)->format('Y'));
        $response->assertSee(Carbon::parse($work->date)->format('n'));
        $response->assertSee(Carbon::parse($work->attendance)->format('H:i'));
        $response->assertSee(Carbon::parse($work->leaving)->format('H:i'));
    
        CarbonImmutable::setTestNow();
    }

    // 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
    public function test_application_attendance_time_validation()
    {
        $user = \App\Models\User::factory()->create([
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

        $response = $this->get("/admin/attendance/{$work->id}");
        $response->assertStatus(200);


        $response = $this->from("/admin/attendance/{$work->id}")->post('/admin/attendance/correction/apply', [
            'work_id' => $work->id,
            'attendance' => '19:00:00',
            'leaving' => '18:00:00',
        ]);
        
        $response->assertSessionHasErrors([
            'attendance' => '出勤時間もしくは退勤時間が不適切な値です',
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

        $work = \App\Models\Work::factory()->create([
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

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/attendance/' . $work->id);
        $response->assertStatus(200);

        $response = $this->from("/admin/attendance/{$work->id}")->post('/admin/attendance/correction/apply', [
            'work_id' => $work->id,
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'remarks' => 'テスト',
            'break_requests' => [
                [
                    'start' => '19:00:00', 
                    'stop'  => '14:00:00',
                ],
            ],
            'update' => 'pending'
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

        $work = \App\Models\Work::factory()->create([
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

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/attendance/' . $work->id);
        $response->assertStatus(200);

        $response = $this->from("/admin/attendance/{$work->id}")->post('/admin/attendance/correction/apply', [
            'work_id' => $work->id,
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'remarks' => 'テスト',
            'break_requests' => [
                [
                    'start' => '13:00:00', 
                    'stop'  => '19:00:00',
                ],
            ],
            'update' => 'pending',
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

        $work = \App\Models\Work::factory()->create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'update' => 'no',
        ]);

        $admin = \App\Models\Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/attendance/' . $work->id);
        $response->assertStatus(200);

        $response = $this->from("/admin/attendance/{$work->id}")->post('/admin/attendance/correction/apply', [
            'work_id' => $work->id,
            'attendance' => '09:00:00',
            'leaving' => '18:00:00',
            'remarks' => '',
        ]);

        $response->assertSessionHasErrors([
            'remarks' => '備考を記入してください',
        ]);
        
        CarbonImmutable::setTestNow();
    }
}
