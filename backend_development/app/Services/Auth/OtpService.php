<?php

namespace App\Services\Auth;

use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    // توليد OTP جديد
    public function generateOtp(User $user)
    {
        // توليد كود 6 أرقام عشوائي
        $otp = rand(100000, 999999);

        // حفظ الكود بشكل مشفر في قاعدة البيانات
        $user->otp_code = Hash::make($otp);
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // إرجاع الكود الأصلي لإرساله للمستخدم
        return $otp;
    }



    // إرسال OTP بالبريد الإلكتروني
    public function sendOtpEmail(User $user, $otp)
    {
        // هنا بنبعت الكود الأصلي اللي اتولد
        Mail::to($user->email)->send(new OtpMail($otp));
    }



    // التحقق من صحة OTP
    public function verifyOtp(User $user, $otp): bool
    {
        // التحقق من وجود كود OTP وصلاحيته
        if (!$user->otp_code || !$user->otp_expires_at) {
            return false;
        }
        // لو الكود انتهت صلاحيته
        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            $this->clearOtp($user);
            return false;
        }

        // التحقق من صحة الكود باستخدام Hash::check
        if (!Hash::check($otp, $user->otp_code)) {
            return false;
        }

        //  الكود صحيح → نصفره ونرجع true
        $this->clearOtp($user);
        return true;
    }



    // مسح OTP من قاعدة البيانات
    public function clearOtp(User $user)
    {
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();
    }
}
