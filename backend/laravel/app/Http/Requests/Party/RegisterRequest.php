<?php

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'theme' => 'required|string|max:30',
            'introduction' => 'required',
            'pref_id' => 'required',
            'place' => 'required',
            'due_max' => 'required',
            'due_date' => 'required',
        ];
    }

    /**
     * バリデーションメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'theme.required' => '題名を入力してください。',
            'theme.string' => '題名は文字列で入力してください。',
            'theme.max' => '題名は30文字以内で入力してください。',

            'introduction.required' => '詳細を入力してください。',

            'pref_id.required' => '都道府県を選択してください。',

            'place.required' => '開催場所を入力してください。',

            'due_max.required' => '定員を選択してください。',

            'due_date.required' => '締切を入力してください。',
        ];
    }
}
