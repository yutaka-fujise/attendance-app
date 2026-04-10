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

            'break1_start' => ['nullable', 'date_format:H:i', 'before_or_equal:clock_out'],
            'break1_end' => ['nullable', 'date_format:H:i', 'before_or_equal:clock_out'],

            'break2_start' => ['nullable', 'date_format:H:i', 'before_or_equal:clock_out'],
            'break2_end' => ['nullable', 'date_format:H:i', 'before_or_equal:clock_out'],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を入力してください。',
            'clock_in.date_format' => '出勤時間を入力してください。',

            'clock_out.required' => '退勤時間を入力してください。',
            'clock_out.date_format' => '退勤時間を入力してください。',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です。',

            'note.required' => '備考を入力してください。',
            'note.max' => '備考は255文字以内で入力してください。',

            'break1_start.date_format' => '休憩時間が不適切な値です。',
            'break2_start.date_format' => '休憩時間が不適切な値です。',
            'break1_start.before_or_equal' => '休憩時間が不適切な値です。',
            'break2_start.before_or_equal' => '休憩時間が不適切な値です。',

            'break1_end.date_format' => '休憩時間もしくは退勤時間が不適切な値です。',
            'break2_end.date_format' => '休憩時間もしくは退勤時間が不適切な値です。',
            'break1_end.before_or_equal' => '休憩時間もしくは退勤時間が不適切な値です。',
            'break2_end.before_or_equal' => '休憩時間もしくは退勤時間が不適切な値です。',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $breaks = [
                ['start' => $this->break1_start, 'end' => $this->break1_end],
                ['start' => $this->break2_start, 'end' => $this->break2_end],
            ];

            foreach ($breaks as $index => $break) {
                $breakStart = $break['start'];
                $breakEnd = $break['end'];

                if (!empty($breakStart) && empty($breakEnd)) {
                    $validator->errors()->add(
                        'break' . ($index + 1) . '_end',
                        '休憩終了時間を入力してください。'
                    );
                }

                if (empty($breakStart) && !empty($breakEnd)) {
                    $validator->errors()->add(
                        'break' . ($index + 1) . '_start',
                        '休憩開始時間を入力してください。'
                    );
                }

                if (!empty($breakStart) && !empty($breakEnd) && $breakEnd <= $breakStart) {
                    $validator->errors()->add(
                        'break' . ($index + 1) . '_end',
                        '休憩時間が不適切な値です。'
                    );
                }
            }
        });
    }
}