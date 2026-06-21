<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * عدد الدقائق اللي يضل فيها الرمز صالح.
     */
    protected int $codeExpiryMinutes = 10;

    /**
     * الخطوة 1: المستخدم نسي كلمة السر، بدخل بريده، منولّد رمز مكوّن من
     * 6 أرقام، منخزنه (مشفّر) بجدول password_reset_tokens، ومنبعتو عبر
     * البريد الإلكتروني يلي أنشأ فيه الحساب.
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // ما منبيّن للمستخدم إذا البريد موجود أو لأ، حفاظاً على الخصوصية
        if (! $user) {
            return response()->json([
                'message' => 'إذا كان البريد الإلكتروني مسجل عنا، رح يوصلك رمز إعادة التعيين.',
            ]);
        }

        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        Mail::to($user->email)->send(new PasswordResetCodeMail($code));

        return response()->json([
            'message' => 'إذا كان البريد الإلكتروني مسجل عنا، رح يوصلك رمز إعادة التعيين.',
        ]);
    }

    /**
     * الخطوة 2: المستخدم بكتب الرمز يلي وصلو. إذا صحيح وضمن مدة الصلاحية،
     * منعطيه إذن مؤقت (عبر السيشن) ينتقل لصفحة تغيير كلمة المرور.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->code, $record->token)) {
            throw ValidationException::withMessages([
                'code' => 'الرمز غير صحيح.',
            ]);
        }

        if (now()->diffInMinutes($record->created_at) > $this->codeExpiryMinutes) {
            throw ValidationException::withMessages([
                'code' => 'انتهت صلاحية الرمز، اطلب رمز جديد.',
            ]);
        }

        // إذن مؤقت يسمح للمستخدم يوصل لصفحة/إندبوينت تغيير كلمة المرور
        $request->session()->put('password_reset_verified_email', $request->email);

        return response()->json([
            'message' => 'تم التحقق من الرمز، فيك هلق تغيّر كلمة المرور.',
        ]);
    }

    /**
     * الخطوة 3: تغيير كلمة المرور، بس إذا المستخدم عدّى من خطوة التحقق
     * من الرمز قبل شوي (نفس السيشن).
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($request->session()->get('password_reset_verified_email') !== $request->email) {
            throw ValidationException::withMessages([
                'email' => 'لازم تتحقق من الرمز أول قبل ما تغيّر كلمة المرور.',
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        $request->session()->forget('password_reset_verified_email');

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح.',
        ]);
    }
}
