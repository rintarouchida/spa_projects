<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ResetPasswordControllerTest extends TestCase
{
    /**
     * sendEmail
     *
     * @test
     * @return void
     */
    public function sendEmail()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('send_email'), [
            'email' => $user->email,
        ]);

        // メールが送信されたことを確認
        Mail::assertSent(ResetPassword::class);

        $response->assertStatus(200)
            ->assertJson(['message' => 'メールを送信しました。']);
    }

    /**
     * resetPassword
     *
     * @test
     * @return void
     */
    public function resetPassword()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('reset_password'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'newpassword',
            'password_confirm' => 'newpassword',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'パスワードの変更が完了しました。']);
    }
}
