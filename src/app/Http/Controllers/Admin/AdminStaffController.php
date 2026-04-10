<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminStaffController extends Controller
{
    public function index()
    {
        $users = User::where('role', 0)->get();

        return view('admin.staff.index', compact('users'));
    }
}
