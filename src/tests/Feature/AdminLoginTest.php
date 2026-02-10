<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_admin_email_required_validation()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => '',
            'password' => 'test1234',
            'password_confirmation' => 'test12345',
        ]);

        $response->assertSessionHasErrors(['email']);

        $this->assertEquals(
            'メールアドレスを入力してください',
            session('errors')->first('email')
        );
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_login_admin_password_required_validation()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'bbb@ccc.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $this->assertEquals(
            'パスワードを入力してください',
            session('errors')->first('password')
        );
    }

    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_login_admin_data_error_validation()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'aaa@gmail.com',
            'password' => 'test1234',
        ]);

        $response->assertSessionHasErrors(['email']);

        $this->assertEquals(
            'ログイン情報が登録されていません',
            session('errors')->first('email')
        );
    }
}
