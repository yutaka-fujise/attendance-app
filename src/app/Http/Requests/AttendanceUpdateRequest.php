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
            'clock_in.date_format' => '出勤時間を入力してください。',

            'clock_out.required' => '退勤時間を入力してください。',
            'clock_out.date_format' => '退勤時間を入力してください。',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です。',

            'note.required' => '備考を入力してください。',
            'note.max' => '備考は255文字以内で入力してください。',

            'breaks.*.break_start.date_format' => '休憩開始時間を入力してください。',
            'breaks.*.break_end.date_format' => '休憩終了時間を入力してください。',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $clockIn = $this->input('clock_in');
            $clockOut = $this->input('clock_out');
            $breaks = $this->input('breaks', []);

            foreach ($breaks as $index => $break) {
                $breakStart = $break['break_start'] ?? null;
                $breakEnd = $break['break_end'] ?? null;

                $startKey = "breaks.$index.break_start";
                $endKey = "breaks.$index.break_end";

                if (!empty($breakStart) && empty($breakEnd)) {
                    $validator->errors()->add($endKey, '休憩終了時間を入力してください。');
                    continue;
                }

                if (empty($breakStart) && !empty($breakEnd)) {
                    $validator->errors()->add($startKey, '休憩開始時間を入力してください。');
                    continue;
                }

                if (empty($breakStart) && empty($breakEnd)) {
                    continue;
                }

                if ($breakEnd <= $breakStart) {
                    $validator->errors()->add($endKey, '休憩時間が不適切な値です。');
                    continue;
                }

                if (!empty($clockIn) && $breakStart < $clockIn) {
                    $validator->errors()->add($startKey, '休憩時間が勤務時間外です。');
                    continue;
                }

                if (!empty($clockOut) && $breakEnd > $clockOut) {
                    $validator->errors()->add($endKey, '休憩時間が勤務時間外です。');
                    continue;
                }
            }
        });
    }
}