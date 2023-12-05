<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

//todo:テスト作成
class UpdateRequest extends FormRequest
{

    public function __construct()
    {
        $id = Auth::id();
    }
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|max:255|'.Rule::unique('users')->ignore($this->auth_id),
            'birthday' => 'nullable|date',
            'pref_id' => 'required',
            'introduction' => 'nullable|string|max:1000',
            'twitter_url' => 'url|nullable|max:255',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => '名前を入力してください。',
            'name.string' => '名前は文字列で入力してください。',
            'name.max' => '名前は255文字以内で入力してください。',

            'email.unique' => 'そのメールアドレスはすでに使われています。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスの形式で入力してください。',
            'email.max' => 'メールアドレスは255文字以内入力してください。',

            'birthday.date' => '生年月日は日付で入力してください。',

            'pref_id.required' => '都道府県を選択してください。',

            'introduction.string' => '自己紹介は文字列で入力してください。',
            'introduction.max' => '自己紹介は1000文字以内で入力してください。',

            'twitter_url.url' => '正しいURLの形式で入力してください。',
            'twitter_url.max' => 'URLは255文字以内で入力してください。',
        ];
    }
}