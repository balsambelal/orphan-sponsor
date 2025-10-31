<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orphan;
use App\Models\Sponsor;
use App\Models\Sponsorship; // إضافة نموذج الكفالات
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية للمدير
     */
    public function dashboard()
    {
        $orphans = Orphan::with('sponsorships')->get();
        $sponsors = Sponsor::withCount('sponsorships')->get();
        $sponsorships = Sponsorship::with(['orphan','sponsor'])->get(); // جلب الكفالات

        return view('admin.dashboard', compact('orphans', 'sponsors', 'sponsorships'));
    }

    /**
     * تبديل حالة التفعيل (مفعل / معطل)
     */
    public function toggleStatus($type, $id)
    {
        $model = $this->getModel($type, $id);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'نوع المستخدم غير صالح.']);
        }

        $model->is_active = !$model->is_active;
        $model->save();

        return response()->json([
            'status' => 'success',
            'is_active' => $model->is_active,
            'message' => $model->is_active ? 'تم تفعيل المستخدم' : 'تم إلغاء التفعيل',
        ]);
    }

    /**
     * تبديل حالة التوثيق (تم التحقق / لم يتم التحقق)
     */
    public function toggleVerify($type, $id)
    {
        $model = $this->getModel($type, $id);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'نوع المستخدم غير صالح.']);
        }

        $model->is_verified = !$model->is_verified;
        $model->save();

        return response()->json([
            'status' => 'success',
            'is_verified' => $model->is_verified,
            'message' => $model->is_verified ? 'تم توثيق البيانات' : 'تم إلغاء التوثيق',
        ]);
    }

    /**
     * دالة مساعدة لجلب النموذج المناسب (يتيم أو كفيل)
     */
    private function getModel($type, $id)
    {
        return match ($type) {
            'orphan' => Orphan::find($id),
            'sponsor' => Sponsor::find($id),
            default => null,
        };
    }

    /**
     * تسجيل خروج المدير
     */
    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * عرض نموذج تسجيل الدخول للمدير
     */
    public function loginForm()
    {
        return view('admin.login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'تم تسجيل الدخول بنجاح.');
        }

        return back()->with('error', 'بيانات الدخول غير صحيحة.');
    }

    /**
     * عرض الكفالات الخاصة باليتيم
     */
    public function showOrphanSponsorships($orphanId)
    {
        $orphan = Orphan::with('sponsorships.sponsor')->findOrFail($orphanId);
        return view('admin.sponsorships', compact('orphan'));
    }

    /**
     * إعادة تعيين كلمة مرور يتيم
     */
    public function forceResetOrphanPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $orphan = Orphan::findOrFail($id);
            $orphan->password = $request->password; // Mutator في النموذج سيشفّر تلقائيًا
            $orphan->save();

            return back()->with([
                'password_success' => 'تم إعادة تعيين كلمة المرور بنجاح.',
                'orphan_id' => $id
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'password_error' => 'فشل إعادة تعيين كلمة المرور: ' . $e->getMessage(),
                'orphan_id' => $id
            ]);
        }
    }

    /**
     * إعادة تعيين كلمة مرور كفيل
     */
    public function forceResetSponsorPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $sponsor = Sponsor::findOrFail($id);
            $sponsor->password = Hash::make($request->password);
            $sponsor->save();

            return back()->with([
                'password_success' => 'تم تعيين كلمة المرور الجديدة للكفيل بنجاح.',
                'sponsor_id' => $id
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'password_error' => 'فشل إعادة تعيين كلمة المرور للكفيل: ' . $e->getMessage(),
                'sponsor_id' => $id
            ]);
        }
    }

    public function deleteUser($type, $id)
    {
        $model = $this->getModel($type, $id);
        if (!$model) {
            return back()->with('error', 'نوع المستخدم غير صالح.');
        }

        try {
            $model->delete();
            return back()->with('success', 'تم حذف المستخدم بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل حذف المستخدم: ' . $e->getMessage());
        }
    }
}
