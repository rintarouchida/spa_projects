<?php

namespace Tests\Unit\app\Http\Requests\Party;

use App\Http\Requests\Party\SearchPartyRequest;
use App\Models\Pref;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SearchPartyRequestTest extends TestCase
{
    protected function setUp() :void
    {
        parent::setUp();
        Pref::factory(['id' => 1])->create();
        Tag::factory(['id' => 1])->create();
    }

    /**
     * @dataProvider validationProvider
     *
     * @test
     * @return void
     */
    public function Validation($inData, $outFail, $outMessage)
    {
        $request = new SearchPartyRequest();
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
                    'pref_id' => 1,
                    'keyword' => str_repeat('a', 30),
                    'tag_id'  => 1,
                ],
                false,
                [],
            ],
            '正常(null)' => [
                [

                ],
                false,
                [],
            ],
            '異常(型違い)' => [
                [
                    'keyword' => 123,
                ],
                true,
                [
                    'keyword' => ['キーワードは文字列で入力してください'],
                ],
            ],
            '異常(文字数オーバー)' => [
                [
                    'keyword' => str_repeat('a', 31),
                ],
                true,
                [
                    'keyword' => ['キーワードは30文字以内で入力してください'],
                ],
            ],
            '異常(存在しない値)' => [
                [
                    'pref_id' => 2,
                    'tag_id'  => 2,
                ],
                true,
                [
                    'pref_id' => ['指定された都道府県は存在しません。'],
                    'tag_id'=> ['指定されたタグは存在しません。']
                ]
            ]

        ];
    }
}
