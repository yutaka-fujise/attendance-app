<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clock_in' => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i', 'after:clock_in'],
            'note' => ['required', 'string', 'max:255'],

            'breaks.*.break_start' => ['nullable', 'date_format:H:i'],
            'breaks.*.break_end' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を入力してください。',
            'clock_in.date_format' => '出勤時間は時刻形式で入力してください。',

            'clock_out.required' => '退勤時間を入力してください。',
            'clock_out.date_format' => '退勤時間は時刻形式で入力してください。',
            'clock_out.after' => '退勤時間は出勤時間より後の時刻を入力してください。',

            'note.required' => '備考を入力してください。',
            'note.max' => '備考は255文字以内で入力してください。',

            'breaks.*.break_start.date_format' => '休憩開始時間は時刻形式で入力してください。',
            'breaks.*.break_end.date_format' => '休憩終了時間は時刻形式で入力してください。',
        ];
    }
    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        foreach ($this->breaks ?? [] as $index => $break) {
            $breakStart = $break['break_start'] ?? null;
            $breakEnd = $break['break_end'] ?? null;

            if (!empty($breakStart) && empty($breakEnd)) {
                $validator->errors()->add(
                    "breaks.$index.break_end",
                    '休憩終了時間を入力してください'
                );
            }

            if (empty($breakStart) && !empty($breakEnd)) {
                $validator->errors()->add(
                    "breaks.$index.break_start",
                    '休憩開始時間を入力してください'
                );
            }

            if (!empty($breakStart) && !empty($breakEnd) && $breakEnd <= $breakStart) {
                $validator->errors()->add(
                    "breaks.$index.break_end",
                    '休憩終了は開始より後にしてください'
                );
            }
        }
    });
}
}
