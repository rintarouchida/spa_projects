<?php

namespace Tests\Unit\app\Http\Requests\Message;

use App\Http\Requests\Message\SendMessageRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SendMessageRequestTest extends TestCase
{
    /**
     * @dataProvider validationProvider
     * @return void
     */
    public function testValidation($inData, $outFail, $outMessage)
    {
        $request = new SendMessageRequest();
        $rules = $request->rules();
        $messages = $request->messages();
        $validator = Validator::make($inData, $rules, $messages);
        $result = $validator->fails();
        $this->assertEquals($outFail, $result);
        $messages = $validator->errors()->getMessages();
        $this->assertEquals($outMessage, $messages);
    }

    //todo:バリデーションが正しいか検証
    public function validationProvider()
    {
        return [
            '正常' => [
                [
                    'content' => str_repeat('a', 255),
                ],
                false,
                [],
            ],
            '異常 文字数オーバー' => [
                [
                    'content' => str_repeat('a', 256),
                ],
                true,
                [
                    'content' => ['メッセージは255文字以内で入力してください。'],
                ],
            ],
            '異常 文字列以外' => [
                [
                    'content' => 123,
                ],
                true,
                [
                    'content' => ['メッセージは文字列で入力してください。'],
                ],
            ],
        ];
    }
}
