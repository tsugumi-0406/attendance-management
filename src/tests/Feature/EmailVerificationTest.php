<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;


class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 会員登録後、認証メールが送信される
    public function test_after_register_send_authentication_email()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]);

        $response->assertRedirect('/email/verify');

        $user = User::where('email', 'bbb@ccc.com')->first();
        $this->assertNotNull($user);

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    // メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
    public function test_transition_authentication_site()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]);
        $response = $this->get('/email/verify');
        $response->assertStatus(200);

        $user = \App\Models\User::where('email', 'bbb@ccc.com')->first();

        $verifyUrl = null;

        Notification::assertSentTo($user, VerifyEmail::class, function ($notification) use ($user, &$verifyUrl) {
            $verifyUrl = $notification->toMail($user)->actionUrl; // 通知が生成した本物のURL
            return true;
        });

        $this->get('/email/verify')->assertStatus(200);

        $this->get($verifyUrl)
            ->assertStatus(302)
            ->assertRedirect('/attendance');
    }

    // メール認証サイトのメール認証を完了すると、勤怠登録画面に遷移する
    public function test_authentication_transition_profile()
    {
        $response = $this->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]);

        $response = $this->get('/email/verify');
        $response->assertStatus(200);

        $user = \App\Models\User::where('email', 'bbb@ccc.com')->first();

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($verifyUrl)
            ->assertRedirect('/attendance');

        $user->refresh();
            $this->assertNotNull($user->email_verified_at);

        $response = $this->actingAs($user)->get('/attendance');
        $response->assertStatus(200);
    }
}
