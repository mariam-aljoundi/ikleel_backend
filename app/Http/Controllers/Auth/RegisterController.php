<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * إنشاء حساب لمستخدم عادي (customer). أصحاب الصالات ومحلات الورد
     * بينترقّى دورهم لـ hall_owner / flower_owner لما يضيفوا كيانهم
     * (صالة أو محل ورد)، مش من هون.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح',
            'user' => $user,
        ], 201);
    }
}
