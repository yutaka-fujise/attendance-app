<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                    'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/',
                ],
                'password' => ['required'],
            ],
            [
                'email.required' => 'メールアドレスを入力してください',
                'email.email' => 'メールアドレスの形式で入力してください',
                'email.regex' => 'メールアドレスの形式で入力してください',
                'password.required' => 'パスワードを入力してください',
            ]
        );

        $credentials['role'] = 1;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('admin.attendances.index');
        }

        return back()
            ->withErrors([
                'email' => 'ログイン情報が登録されていません。',
            ])
            ->onlyInput('email');
    }
}