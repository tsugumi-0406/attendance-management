<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // 名前が入力されていない場合、バリデーションメッセージが表示される
    public function test_register_name_required_validation()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'bbb@ccc.com',
            'password' => 'test12345',
            'password_confirmation' => 'test12345',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_register_email_required_validation()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->from('/register')->post('/register', [
            'name' => 'aaa',
            'email' => '',
            'password' => 'test12345',
            'password_confirmation' => 'test12345',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    // パスワードが7文字以下の場合、バリデーションメッセージが表示される
    public function test_register_password_min_validation()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->from('/register')->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test',
            'password_confirmation' => 'test',
        ]);

        $response->assertSessionHasErrors(['password']);

        $this->assertEquals(
            'パスワードは8文字以上で入力してください',
            session('errors')->first('password')
        );
    }

    // パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    public function test_register_password_confirmed_validation()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->from('/register')->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test12345',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertEquals(
            'パスワードと一致しません',
            session('errors')->first('password')
        );
    }

        // パスワードが入力されていない場合、バリデーションメッセージが表示される
        public function test_register_password_required_validation()
        {
            $response = $this->get('/register');
            $response->assertStatus(200);

            $response = $this->from('/register')->post('/register', [
                'name' => 'aaa',
                'email' => 'bbb@ccc.com',
                'password' => '',
                'password_confirmation' => 'test12345',
            ]);

            $response->assertSessionHasErrors(['password']);
            $this->assertEquals(
                'パスワードを入力してください',
                session('errors')->first('password')
            );
        }

    // 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
    public function test_register_success_redirects_to_profile()
    {
        $this->get('/register')->assertStatus(200);

        $response = $this->followingRedirects()->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'bbb@ccc.com'
        ]);

        $user = User::where('email', 'bbb@ccc.com')->first();
        $this->assertNotNull($user, 'User could not be found after registration');

        $user->forceFill(['email_verified_at' => now()])->save();

        $this->actingAs($user);

        $response = $this->get('/attendance');
        $response->assertStatus(200);
    }
}