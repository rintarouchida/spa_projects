<?php

namespace Tests\Unit\app\Http\Requests\Party;

use App\Http\Requests\Party\SearchPartyRequest;
use App\Models\Pref;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SearchPartyRequestTest extends TestCase
{
    public function setUp() :void
    {
        parent::setUp();
        Pref::factory(['id' => 1])->create();
        Tag::factory(['id' => 1])->create();
        Tag::factory(['id' => 2])->create();
    }

    /**
     * @dataProvider validationProvider
     *
     * @return void
     */
    public function testValidation($inData, $outFail, $outMessage)
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
                    'tag_id'  => [1, 2],
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
                    'pref_id' => 'a',
                    'keyword' => 123,
                    'tag_id'  => [1, 'b'],
                ],
                true,
                [
                    'pref_id' => ['都道府県IDは整数で入力してください。'],
                    'keyword' => ['キーワードは文字列で入力してください'],
                    'tag_id.1'  => ['タグIDは整数で入力してください。'],
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
                    'tag_id'  => [1, 2, 3],
                ],
                true,
                [
                    'pref_id' => ['指定された都道府県は存在しません。'],
                    'tag_id.2'=> ['指定されたタグは存在しません。']
                ]
            ]

        ];
    }
}
