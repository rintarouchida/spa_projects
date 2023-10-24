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
            //todo:もくもく会検索機能のバリデーション作成
        ];
    }
}
