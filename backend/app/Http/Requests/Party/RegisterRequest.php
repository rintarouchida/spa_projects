<?php

namespace App\Http\Requests\Party;

use Carbon\Carbon;
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
            'theme'        => 'required|string|max:30',
            'introduction' => 'required|max:1000',
            'pref_id'      => 'required|exists:prefs,id',
            'place'        => 'required|max:255',
            'due_max'      => 'required',
            'event_date'   => 'required|after:'.Carbon::now()->addWeek()->format('Y-m-d'),
            'tag_ids'      => 'nullable|max:3',
            'tag_ids.*'    => 'nullable|exists:tags,id',
            'image'        => 'nullable|mimes:png,jpg,jpeg',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'theme.required' => '題名を入力してください。',
            'theme.string'   => '題名は文字列で入力してください。',
            'theme.max'      => '題名は30文字以内で入力してください。',

            'introduction.required' => '詳細を入力してください。',
            'introduction.max'      => '詳細は1000文字以内で入力してください。',

            'pref_id.required' => '都道府県を選択してください。',
            'pref_id.exists'   => '指定された都道府県は存在しません。',

            'place.required' => '開催場所を入力してください。',
            'place.max'      => '開催場所は255文字以内で入力してください。',

            'due_max.required'  => '定員を選択してください。',

            'event_date.required' => '開催日時を入力してください。',
            'event_date.after'    => '開催日時は1週間後以降にしてください。',

            'tag_ids.max' => 'タグの選択は3つまでです。',

            'tag_ids.*.exists' => '存在しないタグが含まれています。',

            'image.mimes' => '拡張子がpng,jpg,jpegいずれかのデータを選択してください。',
        ];
    }
}
