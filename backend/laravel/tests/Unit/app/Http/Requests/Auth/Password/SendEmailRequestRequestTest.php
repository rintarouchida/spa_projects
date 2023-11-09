<?php

namespace Tests\Unit\app\Http\Requests\Auth\Password;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\Password\SendEmailRequest;
use App\Models\User;
use Tests\TestCase;

class SendEmailRequestRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::factory([
            'id' => 1,
            'email' => 'test@gmail.com',
        ])->create();
    }

    /**
     * @dataProvider validationProvider
     * @return void
     */
    public function testValidation($inData, $outFail, $outMessage)
    {
        $request = new SendEmailRequest();
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
                    'email' => 'test@gmail.com',
                ],
                false,
                [],
            ],
            '異常 未入力' => [
                [
                ],
                true,
                [
                    'email' => [
                        'メールアドレスを入力してください。'
                    ],
                ],
            ],
            '異常 存在しないアドレス' => [
                [
                    'email' => 'test_test@gmail.com',
                ],
                true,
                [
                    'email' => [
                        '指定されたメールアドレスのユーザーは存在しません。'
                    ],
                ],
            ],
            '異常 メール以外の形式' => [
                [
                    'email' => 'aaa',
                ],
                true,
                [
                    'email' => [
                        '指定されたメールアドレスのユーザーは存在しません。',
                        '正しいメールアドレスの形式で入力してください。',
                    ],
                ],
            ],
        ];
    }
}
