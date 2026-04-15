<?php

return [

    'required' => ':attribute を入力してください。',
    'email' => ':attribute はメール形式で入力してください。',
    'min' => [
        'string' => ':attribute は:min文字以上で入力してください。',
    ],
    'max' => [
        'string' => ':attribute は:max文字以内で入力してください。',
    ],
    'confirmed' => ':attribute と一致しません。',
    'after' => ':attribute は :date より後の時間を入力してください。',

    'attributes' => [
        'name' => 'お名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
        'clock_in' => '出勤時間',
        'clock_out' => '退勤時間',
        'note' => '備考',
    ],

    'custom' => [
        'email' => [
            'required' => 'メールアドレスを入力してください。',
            'email' => 'メールアドレスはメール形式で入力してください。',
            'regex' => 'メールアドレスはメール形式で入力してください。',
            'unique' => 'このメールアドレスは既に登録されています。',
        ],
        'password_confirmation' => [
            'required' => 'パスワード確認を入力してください。',
            'same' => 'パスワードと一致しません。',
        ],
    ],

];