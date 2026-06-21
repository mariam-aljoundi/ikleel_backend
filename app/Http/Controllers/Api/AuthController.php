<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * تسجيل حساب جديد عبر الـ API (مستخدم عادي / customer).
     * بيرجع التوكن مباشرة مشان يقدر يستخدمه بباقي الطلبات.
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
            'api_token' => $this->generateUniqueToken(),
        ]);

        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح',
            'token' => $user->api_token,
            'user' => $user,
        ], 201);
    }

    /**
     * تسجيل الدخول عبر الـ API: بيتحقق من البريد وكلمة السر (مقارنة الـ hash
     * عبر Hash::check)، وبيولّد توكن جديد للمستخدم.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            ]);
        }

        $user->update(['api_token' => $this->generateUniqueToken()]);

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $user->api_token,
            'user' => $user,
        ]);
    }

    /**
     * تسجيل الخروج: بيلغي التوكن الحالي (لازم تكون الراوت محمية بـ
     * middleware api.token مشان نعرف مين المستخدم).
     */
    public function logout(Request $request)
    {
        $request->user()->update(['api_token' => null]);

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    protected function generateUniqueToken(): string
    {
        do {
            $token = Str::random(80);
        } while (User::where('api_token', $token)->exists());

        return $token;
    }
}
