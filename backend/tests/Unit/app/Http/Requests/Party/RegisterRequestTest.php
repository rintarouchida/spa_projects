<?php

namespace Tests\Unit\app\Http\Requests\Party;

use App\Http\Requests\Party\RegisterRequest;
use App\Models\Pref;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Pref::factory([
            'id' => 1
        ])->create();
        Carbon::setTestNow('2023-12-16 10:00:00');
    }

    /**
     * @dataProvider validationProvider
     *
     * @test
     * @return void
     */
    public function Validation($inData, $outFail, $outMessage)
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
                    'theme' => str_repeat('a', 30),
                    'introduction' => '詳細1',
                    'pref_id' => 1,
                    'place' => '場所1',
                    'due_max' => 2,
                    'event_date' => '2023-12-23 10:00:00',
                ],
                false,
                [],
            ],
            '正常 日付一週間以内' => [
                [
                    'theme' => str_repeat('a', 30),
                    'introduction' => '詳細1',
                    'pref_id' => 1,
                    'place' => '場所1',
                    'due_max' => 2,
                    'event_date' => '2023-12-22 10:00:00',
                ],
                true,
                [
                    'event_date' => ['開催日時は1週間後以降にしてください。']
                ],
            ],
            '異常 必須項目未入力' => [
                [],
                true,
                [
                    'pref_id' => ['都道府県を選択してください。'],
                    'theme' => ['題名を入力してください。'],
                    'introduction' => ['詳細を入力してください。'],
                    'place' => ['開催場所を入力してください。'],
                    'due_max' => ['定員を選択してください。'],
                    'event_date' => ['開催日時を入力してください。'],
                ],
            ],
            '異常 異なる型' => [
                [
                    'pref_id' => 1,
                    'theme' => 1,
                    'introduction' => '詳細1',
                    'place' => '場所1',
                    'due_max' => 2,
                    'event_date' => '2023-12-23 10:00:00',
                ],
                true,
                [
                    'theme' => ['題名は文字列で入力してください。'],
                ],
            ],
            '異常 文字数オーバー' => [
                [
                    'pref_id' => 1,
                    'theme' => str_repeat('a', 31),
                    'introduction' => '詳細1',
                    'place' => '場所1',
                    'due_max' => 2,
                    'event_date' => '2023-12-23 10:00:00',
                ],
                true,
                [
                    'theme' => ['題名は30文字以内で入力してください。'],
                ],
            ],
        ];
    }
}
