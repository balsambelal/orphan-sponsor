<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orphan;
use App\Models\Sponsor;
use App\Models\Sponsorship;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrphanController extends Controller
{
    // قائمة الأيتام
    public function index(Request $request)
    {
        $query = Orphan::withCount('sponsorships');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $status = $request->query('sponsorship_status');

        if ($status !== null && $status !== '') {
            if ($status == 1) {
                $query->has('sponsorships'); // مكفولين
            } elseif ($status == 0) {
                $query->doesntHave('sponsorships'); // غير مكفولين
            }
        }

        $orphans = $query->orderBy('name', 'asc')
                         ->paginate(10)
                         ->appends($request->query());

        return view('orphans.index', compact('orphans'));
    }

    // صفحة إضافة يتيم
    public function create()
    {
        return view('orphans.create');
    }

    // حفظ يتيم جديد
   public function store(Request $request)
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
            'سلفيت' => ['بنك فلسطين', 'البنك العربي الفلسطيني']
        ]
    ];

    // تنظيف القيم من الفراغات الزائدة
    $request->merge([
        'country' => trim($request->country),
        'city' => trim($request->city),
        'bank_name' => trim($request->bank_name),
    ]);

    $request->validate([
        'name' => 'required|string|max:255',
        'birthdate' => 'required|date',
        'identity_number' => ['required','string','max:255', Rule::unique('orphans','identity_number')],
        'gender' => 'required|in:ذكر,أنثى',
        'address' => 'nullable|string|max:255',
        'bank_account' => [
            'nullable','string','max:255',
            function ($attribute, $value, $fail) {
                if ($value) {
                    if (Sponsor::where('bank_account', $value)->exists() || Orphan::where('bank_account', $value)->exists()) {
                        $fail('رقم الحساب البنكي مستخدم مسبقًا.');
                    }
                }
            }
        ],
        'notes' => 'nullable|string',
        'child_image' => 'nullable|image|max:2048',
        'birth_certificate' => 'nullable|file|max:4096',
        'death_certificate' => 'nullable|file|max:4096',
        'guardian_id' => 'nullable|file|max:4096',
        'custody_document' => 'nullable|file|max:4096',
        'country' => ['required', Rule::in([$dataRules['country']])],
        'city' => ['required', Rule::in(array_keys($dataRules['cities']))],
        'bank_name' => ['required', function($attribute, $value, $fail) use ($request, $dataRules){
            $city = $request->city;
            if (!isset($dataRules['cities'][$city]) || !in_array($value, $dataRules['cities'][$city])) {
                $fail("اسم البنك غير صالح لهذه المدينة.");
            }
        }],
        'education_status' => ['required', Rule::in(['طفل', 'طالب', 'خريج', 'غير ملتحق'])],
        'password' => 'required|string|min:6|confirmed'
    ]);

    // منع تكرار اليتيم بنفس الاسم وتاريخ الميلاد
    if (Orphan::where('name', $request->name)->where('birthdate', $request->birthdate)->exists()) {
        return back()->withErrors(['name' => 'يوجد يتيم بنفس الاسم وتاريخ الميلاد']);
    }

    $data = $request->only([
        'name','birthdate','gender','address','identity_number',
        'notes','bank_account','country','city','bank_name','education_status'
    ]);

    // رفع الملفات
    $files = ['child_image','birth_certificate','death_certificate','guardian_id','custody_document'];
    foreach ($files as $file) {
        if ($request->hasFile($file)) {
            $data[$file] = $request->file($file)->store('orphans/files', 'public');
        }
    }

    // تشفير كلمة المرور تلقائيًا عبر mutator
    $data['password'] = $request->password;

    Orphan::create($data);

    return redirect()->route('orphans.index')->with('success','تم إضافة اليتيم بنجاح');
}

public function edit(Orphan $orphan)
{
    return view('orphans.edit', compact('orphan'));
}

public function update(Request $request, Orphan $orphan)
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
            'سلفيت' => ['بنك فلسطين', 'البنك العربي الفلسطيني']
        ]
    ];

    // تنظيف القيم
    $request->merge([
        'country' => trim($request->country),
        'city' => trim($request->city),
        'bank_name' => trim($request->bank_name),
    ]);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required','email', Rule::unique('orphans','email')->ignore($orphan->id)],
        'identity_number' => ['required','string', Rule::unique('orphans','identity_number')->ignore($orphan->id)],
        'birthdate' => 'required|date',
        'gender' => 'nullable|string',
        'bank_account' => 'nullable|string|unique:orphans,bank_account,'.$orphan->id,
        'address' => 'nullable|string',
        'notes' => 'nullable|string',
        'child_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'birth_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        'death_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        'guardian_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        'custody_document' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        'country' => ['required', Rule::in([$dataRules['country']])],
        'city' => ['required', Rule::in(array_keys($dataRules['cities']))],
        'bank_name' => ['required', function($attribute, $value, $fail) use ($request, $dataRules){
            $city = $request->city;
            if (!isset($dataRules['cities'][$city]) || !in_array($value, $dataRules['cities'][$city])) {
                $fail("اسم البنك غير صالح لهذه المدينة.");
            }
        }],
        'education_status' => ['required', Rule::in(['طفل', 'طالب', 'خريج', 'غير ملتحق'])],
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $fields = [
        'name','email','identity_number','birthdate','gender',
        'bank_account','address','notes','country','city','bank_name','education_status'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $request->input($field);
    }

    // رفع الملفات
    $files = ['child_image','birth_certificate','death_certificate','guardian_id','custody_document'];
    foreach ($files as $file) {
        if ($request->hasFile($file)) {
            if ($orphan->$file && Storage::disk('public')->exists($orphan->$file)) {
                Storage::disk('public')->delete($orphan->$file);
            }
            $data[$file] = $request->file($file)->store("orphans/{$orphan->id}", 'public');
        }
    }

    // تحديث كلمة المرور إذا تم إدخالها
    if ($request->filled('password')) {
        $orphan->password = $request->password; // سيشفّر تلقائيًا في mutator
    }

    $orphan->update($data);

    return redirect()->route('orphans.dashboard')->with('success','تم تحديث بيانات اليتيم بنجاح!');
}
    // حذف يتيم
    public function destroy(Orphan $orphan)
    {
        $orphan->delete();
        return redirect()->route('orphans.index')->with('success','تم حذف اليتيم');
    }

    // لوحة تحكم اليتيم
    public function dashboard()
    {
        $orphan = Auth::guard('orphan')->user();

        if (!$orphan) {
            return redirect()->route('orphan.login')->with('error','الرجاء تسجيل الدخول أولاً.');
        }

        $sponsorships = $orphan->sponsorships()->latest()->get();

        return view('orphans.dashboard', compact('orphan','sponsorships'));
    }

    // عرض الكفالات الخاصة باليتيم
    public function showSponsorships($orphanId)
    {
        $orphan = Orphan::with('sponsorships.sponsor')->findOrFail($orphanId);

        if (auth()->guard('orphan')->id() != $orphan->id){
            abort(403);
        }

        return view('orphans.sponsorships', compact('orphan'));
    }

    // عرض تفاصيل اليتيم للكفيل
    public function showForSponsor($id)
    {
        $orphan = Orphan::findOrFail($id);
        return view('orphans.show_sponsor', compact('orphan'));
    }

    // كفالة اليتيم
    public function sponsor($id)
    {
        $orphan = Orphan::findOrFail($id);

        if (!$orphan->isSponsored()) {
            $orphan->sponsorship()->create([
                'sponsor_id' => auth()->id(),
                'status' => 'active',
                'start_date' => now(),
            ]);
            return redirect()->route('sponsor.orphan.show', $id)
                             ->with('success','تم كفالة اليتيم بنجاح!');
        }

        return redirect()->route('sponsor.orphan.show', $id)
                         ->with('error','اليتيم مكفول بالفعل.');
    }

    // إحصائيات
    public function statistics()
    {
        $totalOrphans = Orphan::count();
        $sponsoredOrphans = Orphan::whereHas('sponsorships')->count();
        $unsponsoredOrphans = $totalOrphans - $sponsoredOrphans;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $sponsoredThisMonth = Sponsorship::whereYear('created_at',$currentYear)
                                         ->whereMonth('created_at',$currentMonth)
                                         ->count();

        return view('orphans.statistics', compact(
            'totalOrphans','sponsoredOrphans','unsponsoredOrphans','sponsoredThisMonth'
        ));
    }
}

