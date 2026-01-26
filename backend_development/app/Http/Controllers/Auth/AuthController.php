<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\OtpService;
use Illuminate\Http\Request;

class AuthController extends Controller
{   // Dependency Injection لل Services , بستدعى الخدمات فى الكونستركتور
    public function __construct(protected AuthService $authService, protected OtpService $otpService) {}


    // تسجيل مستخدم جديد
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated()); // تسجيل المستخدم
        $otp = $this->otpService->generateOtp($user); // إنشاء OTP للمستخدم
        $this->otpService->sendOtpEmail($user, $otp); // إرسال OTP عبر البريد الإلكتروني

        return ApiResponse::success(
            'User registered successfully. Please verify your email using the OTP sent.',
            ['user_id' => $user->id],
            201
        );
    }



    // التحقق من OTP
    public function verifyOtp(VerifyOtpRequest $request)
    {

        // إيجاد المستخدم عن طريق الاى دى أو رمي خطأ 404
        $user = User::findOrFail($request->input('user_id'));

        // لو الكود غلط أو منتهى الصلاحية
        if (!$this->otpService->verifyOtp($user, $request->input('otp_code'))) {
            return response()->json(['message' => 'Invalid or expired OTP'], 422);
        }

        // مسح OTP بعد التحقق
        $this->otpService->clearOtp($user); // دى انا مسجلها فى ال OTP service

        // تفعيل البريد الإلكتروني
        $this->authService->verifyEmail($user); // دى انا مسجلها فى ال Auth service

        // إنشاء Token جديد للمستخدم
        $token = $user->createToken('auth_token')->plainTextToken;


        return ApiResponse::success(
            'Email verified successfully.',
            ['access_token' => $token,],
            200
        );
    }



    // تسجيل الدخول
    public function login(LoginRequest $request)
    {   // بجيب اليوزر من ال Auth service
        $user = $this->authService->login($request->validated());

        // لو اليوزر مش موجود
        if (!$user) {
            return ApiResponse::error('Invalid credentials', [], 401);
        }

        // لو الايميل مش متفعل
        if ($user === 'email_not_verified') {
            return ApiResponse::error('Email not verified. Please verify your email before logging in.', [], 403);
        }

        // لو كل حاجة تمام برجع اليوزر والتوكن
        return ApiResponse::success(
            'Login successful',
            [
                'user' => $user['user'],
                'access_token' => $user['access_token']
            ],
        );
    }



    // تسجيل الخروج
    public function logout(Request $request)
    {
        $user = $request->user(); // جلب المستخدم الحالي
        $user->tokens()->delete(); // حذف كل التوكنات الخاصة بالمستخدم
        return ApiResponse::success('Logged out successfully');
    }


    // 1️⃣ طلب نسيان كلمة المرور
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = $this->authService->forgetPassword($request->email);
        $otp = $this->otpService->generateOtp($user);
        $this->otpService->sendOtpEmail($user, $otp);
        return ApiResponse::success('OTP sent to your email', ['user_id' => $user->id,], 200);
    }


    // 2️⃣ reset password
    public function resetPassword(ResetPasswordRequest $request)
    {
        // جلب المستخدم عن طريق الايميل
        $user = $this->authService->forgetPassword($request->email);

        // التحقق من ال OTP
        if (!$this->otpService->verifyOtp($user, $request->otp)) {
            return ApiResponse::error('Invalid or expired OTP', 422);
        }

        $this->authService->resetPassword($user, $request->password);
        return ApiResponse::success('Password has been reset successfully.', [], 200);
    }
}
