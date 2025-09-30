<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;
use App\Models\Sponsorship;
use App\Models\Orphan; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class SponsorController extends Controller
{

    // عرض صفحة تعديل الكفيل
public function edit()
{
    $sponsor = Auth::guard('sponsor')->user();
    return view('sponsors.edit', compact('sponsor'));
}


// تحديث بيانات الكفيل
public function update(Request $request)
{
    $sponsor = Auth::guard('sponsor')->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:sponsors,email,' . $sponsor->id,
        'bank_account' => 'nullable|string|max:255',
    ]);

    $sponsor->update($request->only('name','email','bank_account'));

    // استخدم redirect بدلاً من return view
    return redirect()->route('sponsor.edit')
                     ->with('success', 'تم تحديث البيانات بنجاح');
}


    // صفحة تسجيل الكفيل
    public function showRegister()
    {
        return view('sponsors.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sponsors,email',
            'bank_account' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        Sponsor::create([
            'name' => $request->name,
            'email' => $request->email,
            'bank_account' => $request->bank_account,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('sponsor.login')->with('success', 'تم التسجيل بنجاح، الرجاء تسجيل الدخول');
    }

    // صفحة تسجيل الدخول
    public function showLogin()
    {
        return view('sponsors.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $sponsor = Sponsor::where('email', $request->email)->first();

        if ($sponsor && Hash::check($request->password, $sponsor->password)) {
            auth()->guard('sponsor')->login($sponsor);
            return redirect()->route('sponsor.dashboard');
        }

        return back()->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']);
    }


public function dashboard(Request $request)
{
    $query = Orphan::query();

    // فلترة حسب الاسم
    if ($request->filled('name')) {
        $query->where('name', 'like', '%'.$request->name.'%');
    }

    // فلترة حسب حالة الكفالة
    if ($request->filled('sponsorship_status')) {
        if ($request->sponsorship_status == 'sponsored') {
            $query->whereHas('sponsorships');
        } elseif ($request->sponsorship_status == 'unsponsored') {
            $query->doesntHave('sponsorships');
        }
    }

    $orphans = $query->get(); // جميع الأيتام بعد الفلترة

    // جميع الأيتام المكفولين للكفيل الحالي
    $sponsoredOrphans = Sponsorship::with('orphan')
                            ->where('sponsor_id', auth()->guard('sponsor')->id())
                            ->get();

    return view('sponsors.dashboard', compact('orphans', 'sponsoredOrphans'));
}



public function sponsorOrphan(Request $request, $id)
{
    $orphan = Orphan::findOrFail($id);

    if($orphan->is_sponsored) {
        return redirect()->route('sponsor.sponsorship.create', $id)->with('error', 'هذا اليتيم مكفول بالفعل.');
    }

    // إنشاء كفالة جديدة
    Sponsorship::create([
        'orphan_id' => $orphan->id,
        'sponsor_id' => auth()->guard('sponsor')->id(),
        'start_date' => now(),
        'status' => 'active',
    ]);

    // تحديث حالة اليتيم
    $orphan->update(['is_sponsored' => true]);

    return redirect()->route('sponsor.sponsorship.create', $id)->with('success', 'تم كفالة اليتيم بنجاح.');
}


public function index()
{
    // مهم استخدام with('sponsorships')
    $orphans = Orphan::with('sponsorships')->get();

    return view('sponsors.orphans.index', compact('orphans'));
}

public function showOrphan($id)
{
    $orphan = Orphan::with('sponsorships')->findOrFail($id);

    // تحقق من أن المستخدم الحالي مسجل ككفيل
    $isSponsor = auth()->guard('sponsor')->check();

    return view('orphans.show_sponsor', compact('orphan', 'isSponsor'));
}
  
public function redirectToSponsorshipForm($id)
{
    // إعادة التوجيه لصفحة إنشاء كفالة مع معرف اليتيم
    return redirect()->route('sponsor.sponsorship.create', $id);
}

public function createSponsorship($id)
{
    $orphan = Orphan::findOrFail($id);

    // التأكد أن اليتيم غير مكفول مسبقًا
    if ($orphan->sponsorships()->exists()) {
        return redirect()->route('sponsor.dashboard')
            ->with('error', 'هذا اليتيم مكفول بالفعل.');
    }

    return view('sponsors.create_sponsorship', compact('orphan'));
}

public function storeSponsorship(Request $request, $orphan_id)
{
    $sponsor = Auth::guard('sponsor')->user();
    $orphan = Orphan::findOrFail($orphan_id);

    // تحقق إذا كان اليتيم مكفول مسبقًا
    if ($orphan->sponsorships()->where('sponsor_id', $sponsor->id)->exists()) {
        return redirect()->back()->with('error', 'لقد قمت بكفالة هذا الطفل مسبقًا.');
    }

    // تحقق صحة البيانات
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // حفظ الكفالة
    $orphan->sponsorships()->create([
        'sponsor_id' => $sponsor->id,
        'amount' => $request->amount,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
    ]);

    // تحديث حالة اليتيم (اختياري)
    $orphan->is_sponsored = 1;
    $orphan->save();

    return redirect()->route('sponsor.dashboard')->with('success', 'تم تسجيل الكفالة بنجاح.');
}


// عرض جميع الكفالات الخاصة بالكفيل الحالي
    public function sponsorships()
    {
        $sponsor = auth()->guard('sponsor')->user();

        $sponsorships = Sponsorship::with(['orphan', 'sponsor'])
            ->where('sponsor_id', $sponsor->id)
            ->get();

        return view('sponsors.sponsorships', compact('sponsorships'));
    }

    // عرض تفاصيل كفالة معينة
    public function showSponsorship($id)
    {
        $sponsorship = Sponsorship::with(['orphan', 'sponsor'])->findOrFail($id);

        return view('sponsors.show_sponsorship', compact('sponsorship'));
    }

}

