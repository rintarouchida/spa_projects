<?php

namespace Tests\Unit\app\Http\Requests\Auth\Password;

use App\Http\Requests\Auth\Password\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ResetPasswordRequestTest extends TestCase
{

    /**
     * @dataProvider validationProvider
     *
     * @test
     * @return void
     */
    public function Validation($inData, $outFail, $outMessage)
    {
        $request = new ResetPasswordRequest();
        $rules = $request->rules();
        $messages = $request->messages();
        $validator = Validator::make($inData, $rules, $messages);
        $result = $validator->fails();
        $this->assertEquals($outFail, $result);
        $messages = $validator->errors()->getMessages();
        $this->assertEquals($outMessage, $messages);
    }

    public function validationProvider()
    {
        return [
            '正常' => [
                [
                    'password'         => 'new_password',
                    'password_confirm' => 'new_password',
                ],
                false,
                [],
            ],
            '異常 未入力' => [
                [
                    'password'         => null,
                    'password_confirm' => null,
                ],
                true,
                [
                    'password'         => [
                        'パスワードを入力してください。',
                    ],
                    'password_confirm' => [
                        'パスワード(確認用)を入力してください。',
                    ],
                ],
            ],
            '異常 8文字未満' => [
                [
                    'password'         => str_repeat('a', 7),
                    'password_confirm' => str_repeat('a', 7),
                ],
                true,
                [
                    'password'         => [
                        'パスワードは8文字以上で入力してください。',
                    ],
                    'password_confirm' => [
                        'パスワード(確認用)は8文字以上で入力してください。',
                    ],
                ],
            ],
            '異常 255文字異常' => [
                [
                    'password'         => str_repeat('a', 256),
                    'password_confirm' => str_repeat('a', 256),
                ],
                true,
                [
                    'password'         => [
                        'パスワードは255文字以下で入力してください。',
                    ],
                    'password_confirm' => [
                        'パスワード(確認用)は255文字以下で入力してください。',
                    ],
                ],
            ],
            '異常 文字列以外' => [
                [
                    'password'         => 12345678,
                    'password_confirm' => 12345678,
                ],
                true,
                [
                    'password'         => [
                        'パスワードは文字列で入力してください。',
                    ],
                    'password_confirm' => [
                        'パスワード(確認用)は文字列で入力してください。',
                    ],
                ],
            ],
            '異常 パスワードとパスワード(確認用)が合致しない' => [
                [
                    'password'         => 'password',
                    'password_confirm' => 'new_password',
                ],
                true,
                [
                    'password_confirm' => [
                        'パスワード(確認用)とパスワードが合致しません。',
                    ],
                ],
            ],
        ];
    }
}
