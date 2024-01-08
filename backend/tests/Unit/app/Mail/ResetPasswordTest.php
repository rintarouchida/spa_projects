<?php

use Config;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Mail\ResetPassword;

class ResetPasswordTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * ResetPasswordMail
     *
     * @test
     * @return void
     */
    public function ResetPasswordMail()
    {
        Config::set('app.front_url', 'https://test');
        $email = 'test@example.com';
        $token = 'test_token';
        $name = 'Test User';

        $mail = new ResetPassword($email, $token, $name);
        $builtMail = $mail->build();

        $this->assertInstanceOf(ResetPassword::class, $mail);
        $this->assertEquals($email, $builtMail->to[0]['address']);
        $this->assertEquals('mokumoku_map@gmail.com', $builtMail->from[0]['address']);
        $this->assertEquals('もくもくMAP', $builtMail->from[0]['name']);
        $this->assertEquals('パスワード再設定をお願いします。', $builtMail->subject);
        $this->assertStringContainsString('https://test/auth/edit_password/'.$token.'?email='.$email, $builtMail->viewData['url']);
        $this->assertEquals($name, $builtMail->viewData['name']);
    }
}
