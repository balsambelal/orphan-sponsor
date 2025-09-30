<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orphan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Sponsorship;


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
    //  التحقق من صحة البيانات
    $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|email|unique:orphans,email',
        'identity_number'   => 'required|string|unique:orphans,identity_number',
        'password'          => 'required|string|confirmed|min:6',
        'birthdate'         => 'required|date',
        'gender'            => 'nullable|string',
        'bank_account'      => 'nullable|string',
        'address'           => 'nullable|string',
        'notes'             => 'nullable|string',
        'child_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'birth_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'death_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'guardian_id'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'custody_document'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
    ]);

    // تجهيز البيانات الأساسية
    $data = $request->only([
        'name','email','identity_number','password','birthdate',
        'gender','bank_account','address','notes'
    ]);
    $data['password'] = bcrypt($data['password']);

    // رفع صورة الطفل
    if ($request->hasFile('child_image')) {
        $data['child_image'] = $request->file('child_image')
            ->store('orphans', 'public'); 
    }

    // رفع المستندات
    $documents = ['birth_certificate','guardian_id','custody_document','death_certificate'];
    foreach ($documents as $doc) {
        if ($request->hasFile($doc)) {
            $data[$doc] = $request->file($doc)
                ->store('documents', 'public'); 
        }
    }

    // إنشاء السجل
    $orphan = Orphan::create($data);

    // تسجيل الدخول
    Auth::guard('orphan')->login($orphan);

    return redirect()->route('orphans.dashboard');
}



    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('orphan')->attempt($credentials)) {
            return redirect()->route('orphans.dashboard');
        }
        return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
    }

    public function logout()
{
    Auth::guard('orphan')->logout();
    return redirect()->route('orphans.login')->with('success', 'تم تسجيل الخروج بنجاح.');
}

    public function dashboard() {
        $orphan = Auth::guard('orphan')->user();

    // جلب الكفالة الخاصة باليتيم الحالي إذا وجدت
    $sponsorship = Sponsorship::where('orphan_id', $orphan->id)->first();

    // إذا لم توجد كفالة، اجعل المتغير null لتجنب الخطأ في Blade
    if (!$sponsorship) {
        $sponsorship = null;
    }

    return view('orphans.dashboard', compact('orphan', 'sponsorship'));
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

