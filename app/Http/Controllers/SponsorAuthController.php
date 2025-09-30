<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SponsorAuthController extends Controller
{
    // 1️⃣ عرض صفحة تسجيل الدخول للكفيل
    public function showLogin()
    {
        return view('sponsors.login'); // تأكد من وجود هذا view
    }

    // 2️⃣ تسجيل الدخول
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('sponsor')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('sponsor.dashboard'));
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة',
        ]);
    }

    // 3️⃣ تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::guard('sponsor')->logout(); // خروج الكفيل
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sponsor.login'); // إعادة التوجيه لصفحة تسجيل دخول الكفيل
    }
}
