<?php

namespace App\Http\Requests\Auth\Password;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'email' => 'required|exists:users,email|email|',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.exists'   => '指定されたメールアドレスのユーザーは存在しません。',
            'email.email'    => '正しいメールアドレスの形式で入力してください。',
        ];
    }
}
