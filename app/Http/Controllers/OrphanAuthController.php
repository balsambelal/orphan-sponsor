<?php
namespace App\Http\Controllers;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use App\Models\Orphan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Sponsorship;
use Illuminate\Validation\Rule;



class OrphanAuthController extends Controller
{
    public function showRegisterForm() {
        return view('orphans.register');
    }
    public function showLoginForm() {
    return view('orphans.login');
}

public function register(Request $request)
{
    $dataRules = [
        'country' => 'فلسطين',
        'cities' => [
            'القدس' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني', 'بنك الاتحاد', 'البنك العربي الإسلامي'],
            'رام الله' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني', 'بنك الاتحاد'],
            'غزة' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
            'خانيونس' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
            'رفح' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
            'الوسطى' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
            'الخليل' => ['بنك فلسطين', 'البنك الوطني'],
            'بيت لحم' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني'],
            'نابلس' => ['بنك فلسطين', 'بنك الاتحاد'],
            'جنين' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني'],
            'طولكرم' => ['بنك فلسطين', 'البنك العربي الإسلامي'],
            'قلقيلية' => ['بنك فلسطين', 'بنك الاتحاد'],
            'أريحا' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني'],
            'سلفيت' => ['بنك فلسطين', 'البنك العربي الإسلامي']
        ]
    ];

    //  التحقق من صحة البيانات
    $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|email|unique:orphans,email',
        'identity_number'   => 'required|string|unique:orphans,identity_number',
        'password'          => 'required|string|confirmed|min:6',
        'birthdate'         => 'required|date',
        'gender'            => 'nullable|string',
        'bank_account'      => [
            'nullable','string','max:255',
            Rule::unique('orphans','bank_account'),
            function ($attribute, $value, $fail) {
                if ($value && Sponsor::where('bank_account', $value)->exists()) {
                    $fail('رقم الحساب البنكي مستخدم مسبقًا من قبل كفيل.');
                }
            }
        ],
        'address'           => 'nullable|string|max:255',
        'notes'             => 'nullable|string',
        'country'           => ['required', Rule::in(['فلسطين'])],
        'city'              => ['required', Rule::in(array_keys($dataRules['cities']))],
        'bank_name'         => ['required', function ($attribute, $value, $fail) use ($request, $dataRules) {
            if (!in_array($value, $dataRules['cities'][$request->city] ?? [])) {
                $fail("اسم البنك غير صالح لهذه المدينة.");
            }
        }],
        'education_status'  => ['required', Rule::in(['طفل','طالب', 'خريج', 'غير ملتحق'])],
        'child_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'birth_certificate' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        'death_certificate' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        'guardian_id'       => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        'custody_document'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
    ]);

    // تحقق إضافي من تكرار بيانات اليتيم (الاسم + تاريخ الميلاد)
    if (Orphan::where('name', $request->name)
        ->where('birthdate', $request->birthdate)
        ->exists()) {
        return back()->withErrors(['name' => 'يوجد يتيم بنفس الاسم وتاريخ الميلاد'])->withInput();
    }

    //  تجهيز البيانات
    $data = $request->only([
        'name','email','identity_number','birthdate','gender',
        'bank_account','address','notes','country','city',
        'bank_name','education_status'
    ]);

    $data['password'] = Hash::make($request->password);

    // رفع صورة الطفل
    if ($request->hasFile('child_image')) {
        $data['child_image'] = $request->file('child_image')->store('orphans/images', 'public'); 
    }

    // رفع المستندات
    $documents = ['birth_certificate','guardian_id','custody_document','death_certificate'];
    foreach ($documents as $doc) {
        if ($request->hasFile($doc)) {
            $data[$doc] = $request->file($doc)->store('orphans/documents', 'public'); 
        }
    }

    // إنشاء سجل اليتيم
    $orphan = Orphan::create($data);

    // تسجيل الدخول بعد التسجيل
    Auth::guard('orphan')->login($orphan);

    return redirect()->route('orphans.dashboard')->with('success', 'تم التسجيل بنجاح!');
}


public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('orphan')->attempt($credentials)) {
        $orphan = Auth::guard('orphan')->user();

        // تحقق من حالة الحساب
        if (!$orphan->is_active) {
            Auth::guard('orphan')->logout();
            return back()->withErrors(['email' => 'حسابك معطل. لا يمكنك تسجيل الدخول.']);
        }

        return redirect()->route('orphans.dashboard');
    }

    return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
}




    public function logout()
{
    Auth::guard('orphan')->logout();
    return redirect()->route('orphans.login')->with('success', 'تم تسجيل الخروج بنجاح.');
}


public function dashboard()
{
    $orphan = Auth::guard('orphan')->user();

    if (!$orphan) {
        return redirect()->route('orphan.login')->with('error', 'الرجاء تسجيل الدخول أولاً.');
    }

    $sponsorships = $orphan->sponsorships()->latest()->get();

    return view('orphans.dashboard', compact('orphan', 'sponsorships'));
}

    public function listOrphans()
{
    // جلب جميع الأيتام وحساب العمر ديناميكيًا
    $orphans = Orphan::all()->map(function($orphan) {
        $orphan->age = Carbon::parse($orphan->birthdate)->age;
        return $orphan;
    });

    return view('orphans.list', compact('orphans'));
}
}

