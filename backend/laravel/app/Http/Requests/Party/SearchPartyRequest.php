<?php

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;

class SearchPartyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword'  => 'nullable|string|max:30',
            'pref_id'  => 'nullable|integer|exists:prefs,id',
            'tag_ids.*' => 'nullable|integer|exists:tags,id',
        ];
    }

    /**
     * バリデーションメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'keyword.string'  => 'キーワードは文字列で入力してください',
            'keyword.max'     => 'キーワードは30文字以内で入力してください',

            'pref_id.integer'     => '都道府県IDは整数で入力してください。',
            'pref_id.exists'  => '指定された都道府県は存在しません。',

            'tag_ids.*.integer'    => 'タグIDは整数で入力してください。',
            'tag_ids.*.exists' => '指定されたタグは存在しません。',
        ];
    }
}
