<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // Allow all users to make this request
        // لازم اخليها true عشان اي حد يقدر يعمل تسجيل دخول   
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array // دي الدوال بتحدد القوانين اللي لازم تتبعها البيانات اللي جايه من الفورم
    {
        return [
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6|confirmed',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_bio'         => 'nullable|string|max:1000',
            'phone_number'     => 'nullable|string|max:20',
        ];
    }

    public function messages(): array // دي الدوال بتحدد الرسائل اللي هتظهر لو في خطأ في البيانات اللي جايه من الفورم   
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.unique' => 'The email address is already registered.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
