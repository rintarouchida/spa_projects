<?php

namespace Tests\Unit\app\Http\Requests\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Tests\TestCase;

class RegisterRequestTest extends TestCase
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
        $request = new RegisterRequest();
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
                    'name' => str_repeat('a', 255),
                    'email' => 'test1@gmail.com',
                    'password' => str_repeat('a', 255),
                    'birthday' => '2023-05-08',
                    'pref_id' => 1,
                    'introduction' => str_repeat('a', 1000),
                    'twitter_url' => 'https://test.com',
                ],
                false,
                [],
            ],
            '正常 必須項目のみ' => [
                [
                    'name' => str_repeat('a', 255),
                    'email' => 'test1@gmail.com',
                    'password' => str_repeat('a', 255),
                    'pref_id' => 1,
                ],
                false,
                [],
            ],

            '異常 必須項目未入力' => [
                [],
                true,
                [
                    'name' => ['名前を入力してください。'],
                    'email' => ['メールアドレスを入力してください。'],
                    'password' => ['パスワードを入力してください。'],
                    'pref_id' => ['都道府県を選択してください。'],
                ],
            ],

            '異常 存在する値' => [
                [
                    'name' => str_repeat('a', 255),
                    'email' => 'test@gmail.com',
                    'password' => str_repeat('a', 255),
                    'pref_id' => 1,
                ],
                true,
                [
                    'email' => ['そのメールアドレスはすでに使われています。'],
                ],
            ],
            '異常 文字数オーバー' => [
                [
                    'name' => str_repeat('a', 256),
                    'email' => str_repeat('a', 256).'test@gmail.com',
                    'password' => str_repeat('a', 256),
                    'introduction' => str_repeat('a', 1001),
                    'pref_id' => 1,
                    'twitter_url' => 'https://'.str_repeat('a', 256).'.com',
                ],
                true,
                [
                    'name' => ['名前は255文字以内で入力してください。'],
                    'email' => ['メールアドレスは255文字以内入力してください。'],
                    'password' => ['パスワードは255文字以下で入力してください。'],
                    'introduction' => ['自己紹介は1000文字以内で入力してください。'],
                    'twitter_url' => ['URLは255文字以内で入力してください。'],
                ],
            ],
            '異常 文字数足りない' => [
                [
                    'name' => str_repeat('a', 255),
                    'email' => 'test1@gmail.com',
                    'password' => str_repeat('a', 7),
                    'pref_id' => 1,
                ],
                true,
                [
                    'password' => ['パスワードは8文字以上で入力してください。'],
                ],
            ],
            '異常 異なる型' => [
                [
                    'name' => 1,
                    'email' => 'aaa',
                    'password' => 11111111,
                    'birthday' => 'aaa',
                    'pref_id' => 1,
                    'introduction' => 1,
                    'twitter_url' => 'aaa',
                ],
                true,
                [
                    'name' => ['名前は文字列で入力してください。'],
                    'email' => ['正しいメールアドレスの形式で入力してください。'],
                    'password' => ['パスワードは文字列で入力してください。'],
                    'birthday' => ['生年月日は日付で入力してください。'],
                    'introduction' => ['自己紹介は文字列で入力してください。'],
                    'twitter_url' => ['正しいURLの形式で入力してください。'],
                ],
            ]
        ];
    }
}
