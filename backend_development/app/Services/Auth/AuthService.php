<?php

namespace App\Services\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data)
    {
        // if (isset($data['profile_image'])) {
        //     $path = $data['profile_image'] = $data['profile_image']->store('profile_images', 'public');
        // }

        $user = User::create([
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'profile_image' => $data['profile_image'] ?? null,
            'user_bio'     => $data['user_bio'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
        ]);

        return $user; // Return the created user instance
    }

    public function verifyEmail(User $user): void //  دى بتاخد اليوزر وبتحدثله عمود التحقق بتاع الايميل
    {
        $user->update([
            'email_verified_at' => Carbon::now(),
        ]);
    }



    public function login(array $data) // تسجيل الدخول
    {   // التحقق من صحة بيانات الدخول
        $user = User::where('email', $data['email'])->first();

        // لو الايميل مش موجود او الباسورد غلط
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception('The provided credentials are incorrect.');
        }

        // لو المستخدم مسجل بس الايميل مش متفعل
        if (!$user->email_verified_at) {
            return 'email_not_verified';
        }

        // إنشاء Token جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }



    public function logout(User $user): void
    {
        // مسح كل التوكنات بتاعت المستخدم
        $user->tokens()->delete();
    }



    public function forgetPassword(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
        return $user;
    }



    public function resetPassword(User $user, string $password): void
    {
        $user->update([
            'password' => Hash::make($password),
        ]);
        // مسح كل التوكنز القديمة
        $user->tokens()->delete();
    }
}
