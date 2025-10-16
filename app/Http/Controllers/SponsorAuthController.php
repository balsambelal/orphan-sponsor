<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sponsor;


class SponsorAuthController extends Controller
{
    //  عرض صفحة تسجيل الدخول للكفيل
    public function showLogin()
    {
        return view('sponsors.login'); // تأكد من وجود هذا view
    }


    //  تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::guard('sponsor')->logout(); // خروج الكفيل
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sponsor.login'); // إعادة التوجيه لصفحة تسجيل دخول الكفيل
    }
}
