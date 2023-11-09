<?php

namespace App\Http\Requests\Auth\Password;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password'         => 'required|string|max:255|min:8',
            'password_confirm' => 'required|string|max:255|min:8|same:password',
        ];
    }

    /**
     * バリデーションメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'password.required'         => 'パスワードを入力してください。',
            'password.string'           => 'パスワードは文字列で入力してください。',
            'password.min'              => 'パスワードは8文字以上で入力してください。',
            'password.max'              => 'パスワードは255文字以下で入力してください。',
            'password_confirm.required' => 'パスワード(確認用)を入力してください。',
            'password_confirm.same'     => 'パスワード(確認用)とパスワードが合致しません。',
            'password_confirm.string'   => 'パスワード(確認用)は文字列で入力してください。',
            'password_confirm.min'      => 'パスワード(確認用)は8文字以上で入力してください。',
            'password_confirm.max'      => 'パスワード(確認用)は255文字以下で入力してください。',
        ];
    }
}
//todo: テスト作成
