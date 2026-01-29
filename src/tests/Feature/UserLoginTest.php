<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_email_required_validation()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => 'test1234',
            'password_confirmation' => 'test12345',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_password_required_validation()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->from('/login')->post('/login', [
            'email' => 'bbb@ccc.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_login_user_data_error_validation()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->from('/login')->post('/login', [
            'email' => 'aaa@gmail.com',
            'password' => 'test1234',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません。',
        ]);
    }
}
