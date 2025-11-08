<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;
use App\Models\Sponsorship;
use App\Models\Orphan; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SponsorController extends Controller
{
    /** ===========================
     *  عرض صفحة تعديل الكفيل
     * =========================== */
    public function edit()
    {
        $sponsor = Auth::guard('sponsor')->user();
       // dd($sponsor->toArray());
        return view('sponsors.edit', compact('sponsor'));
    }


    /** ===========================
     *  تحديث بيانات الكفيل
     * =========================== */
    public function update(Request $request)
{
    $sponsor = Auth::guard('sponsor')->user();

    $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => ['required','email', Rule::unique('sponsors','email')->ignore($sponsor->id)],
        'bank_name'    => 'nullable|string|max:255',
        'bank_account' => ['nullable','string','max:255', Rule::unique('sponsors','bank_account')->ignore($sponsor->id)],
        'country'      => 'nullable|string|max:255',
        'city'         => 'nullable|string|max:255',
        'password'     => 'nullable|string|min:6|confirmed',
    ]);

    // تحقق من أن كلمة المرور الجديدة ليست نفسها القديمة
    if ($request->filled('password') && Hash::check($request->password, $sponsor->password)) {
        return back()->withErrors(['password' => 'كلمة المرور الجديدة لا يمكن أن تكون نفسها القديمة.'])->withInput();
    }

    // بناء بيانات التحديث
    $fields = ['name','email','bank_name','bank_account','country','city'];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $request->input($field);
    }

    // كلمة المرور (سيتم تشفيرها في الـ Mutator)
    if ($request->filled('password')) {
        $sponsor->password = $request->password;
    }

    // تحديث البيانات
    $sponsor->update($data);

    return redirect()->route('sponsor.edit')->with('success', 'تم تحديث البيانات بنجاح.');
}



    /** ===========================
     *  صفحة التسجيل
     * =========================== */
    public function showRegister()
    {
        return view('sponsors.register');
    }

    /** ===========================
     *  تسجيل كفيل جديد (منع التكرار)
     * =========================== */
    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email' => 'required|email|unique:sponsors,email',
            'bank_name'    => 'nullable|string|max:255',
            'bank_account' => 'required|string|unique:sponsors,bank_account',
            'password'     => 'required|string|min:6|confirmed',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'country'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
        ]);

        //  تحقق يدوي من البريد الإلكتروني والحساب البنكي
        if (Sponsor::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => ' البريد الإلكتروني مسجل مسبقاً.'])->withInput();
        }

        if ($request->bank_account && Sponsor::where('bank_account', $request->bank_account)->exists()) {
            return back()->withErrors(['bank_account' => ' رقم الحساب البنكي مستخدم من قبل.'])->withInput();
        }

        // إنشاء الكفيل
        Sponsor::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'bank_name'    => $request->bank_name,
            'bank_account' => $request->bank_account,
            'password'     => Hash::make($request->password),
            'phone'        => $request->phone,
            'address'      => $request->address,
            'country'      => $request->country,
            'city'         => $request->city,
        ]);

        return redirect()->route('sponsor.login')->with('success', ' تم التسجيل بنجاح، الرجاء تسجيل الدخول.');
    }


    /** ===========================
     *  تسجيل الدخول
     * =========================== */
    public function showLogin()
    {
        return view('sponsors.login');
    }

   public function login(Request $request)
{
    // التحقق من صحة البيانات المدخلة
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // محاولة تسجيل الدخول باستخدام guard 'sponsor'
    if (Auth::guard('sponsor')->attempt($credentials)) {

        // جلب بيانات المستخدم لتأكيد حالة الحساب
        $user = Auth::guard('sponsor')->user();

        if (!$user->is_active) {
            // تسجيل الخروج وتدمير الجلسة بالكامل
            Auth::guard('sponsor')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->back()->with('error', 'حسابك معطل. لا يمكنك تسجيل الدخول.');
        }

        // تجديد الجلسة بعد تسجيل الدخول الناجح
        $request->session()->regenerate();

        // إعادة التوجيه إلى لوحة تحكم الكفلاء
        return redirect()->route('sponsor.dashboard');
    }

    // في حالة فشل تسجيل الدخول
    return redirect()->back()->with('error', 'بيانات الدخول غير صحيحة.');
}


    /** ===========================
     *  لوحة التحكم + الفلاتر
     * =========================== */
    public function dashboard(Request $request)
    {
        $query = Orphan::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('age')) {
            $age = (int) $request->age;
            $startDate = Carbon::now()->subYears($age + 1)->addDay();
            $endDate = Carbon::now()->subYears($age);
            $query->whereBetween('birthdate', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }

        if ($request->filled('education_status')) {
            $query->where('education_status', $request->education_status);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('sponsorship_status')) {
            if ($request->sponsorship_status == 'sponsored') {
                $query->whereHas('sponsorships');
            } elseif ($request->sponsorship_status == 'unsponsored') {
                $query->doesntHave('sponsorships');
            }
        }

        $orphans = $query->paginate(10);
        return view('sponsors.dashboard', compact('orphans'));
    }

    /** ===========================
     *  إدارة الكفالات
     * =========================== */
    public function sponsorOrphan(Request $request, $id)
    {
        $orphan = Orphan::findOrFail($id);

        if ($orphan->is_sponsored) {
            return redirect()->route('sponsor.sponsorship.create', $id)->with('error', 'هذا اليتيم مكفول بالفعل.');
        }

        Sponsorship::create([
            'orphan_id' => $orphan->id,
            'sponsor_id' => auth()->guard('sponsor')->id(),
            'start_date' => now(),
            'status' => 'active',
        ]);

        $orphan->update(['is_sponsored' => true]);

        return redirect()->route('sponsor.sponsorship.create', $id)->with('success', 'تم كفالة اليتيم بنجاح.');
    }

    public function index()
    {
        $sponsor = auth()->guard('sponsor')->user();

        $sponsorships = Sponsorship::with(['orphan', 'sponsor'])
            ->where('sponsor_id', $sponsor->id)
            ->paginate(10);

        return view('sponsorships.index', compact('sponsorships'));
    }
public function showOrphan($id)
{
    $orphan = Orphan::findOrFail($id);
    $currentSponsor = auth()->guard('sponsor')->user();

    // تحقق إذا الكفيل الحالي مكفل للطفل
    $isSponsor = false;
    if($currentSponsor) {
        $isSponsor = $orphan->sponsorships()
                             ->where('sponsor_id', $currentSponsor->id)
                             ->exists();
    }

    return view('orphans.show_sponsor', compact('orphan', 'isSponsor'));
}

public function showOrphanForSponsor($id)
{
    $orphan = Orphan::findOrFail($id);
    $sponsor = auth()->guard('sponsor')->user();

    // تحقق هل الكفيل الحالي مكفل للطفل بالفعل
    $isSponsor = $orphan->sponsorships()->where('sponsor_id', $sponsor->id)->exists();

    return view('orphans.show_sponsor', compact('orphan', 'isSponsor'));
}


    public function redirectToSponsorshipForm($id)
    {
        return redirect()->route('sponsor.sponsorship.create', $id);
    }

    public function createSponsorship($orphanId)
{
    $orphan = Orphan::findOrFail($orphanId);
    $currentSponsor = auth()->guard('sponsor')->user();

    // منع الكفيل الحالي من تكرار الكفالة
    $alreadySponsoredByCurrent = $orphan->sponsors()->where('sponsor_id', $currentSponsor->id)->exists();
    if ($alreadySponsoredByCurrent) {
        return redirect()->route('sponsor.dashboard')
                         ->with('error', 'لقد كفلت هذا اليتيم بالفعل.');
    }

    // السماح بالكفالة حتى لو الطفل مكفول من كفيل آخر
    return view('sponsors.create_sponsorship', compact('orphan'));
}


    public function storeSponsorship(Request $request, $orphan_id)
    {
        $sponsor = Auth::guard('sponsor')->user();
        $orphan = Orphan::findOrFail($orphan_id);

        if ($orphan->sponsorships()->where('sponsor_id', $sponsor->id)->exists()) {
            return redirect()->back()->with('error', 'لقد قمت بكفالة هذا الطفل مسبقًا.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $orphan->sponsorships()->create([
            'sponsor_id' => $sponsor->id,
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $orphan->update(['is_sponsored' => 1]);

        return redirect()->route('sponsor.dashboard')->with('success', 'تم تسجيل الكفالة بنجاح.');
    }

    public function sponsorships()
    {
        $sponsor = auth()->guard('sponsor')->user();

        $sponsorships = Sponsorship::with(['orphan', 'sponsor'])
            ->where('sponsor_id', $sponsor->id)
            ->get();

        return view('sponsors.sponsorships', compact('sponsorships'));
    }

    public function showSponsorship($id)
    {
        $sponsorship = Sponsorship::with(['orphan', 'sponsor'])->findOrFail($id);
        return view('sponsors.show_sponsorship', compact('sponsorship'));
    }
}
