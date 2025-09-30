<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orphan;
use App\Models\Sponsorship;
use Illuminate\Support\Facades\Storage;

class OrphanController extends Controller
{
    // قائمة الايتام
   // app/Http/Controllers/OrphanController.php
// app/Http/Controllers/OrphanController.php
public function index(Request $request)
    {
        $query = Orphan::withCount('sponsorships');

if ($request->filled('name')) {
    $query->where('name', 'like', '%' . $request->input('name') . '%');
}

$status = $request->query('sponsorship_status');

if ($status !== null && $status !== '') {
    if ($status == 1) {
        // فقط الأيتام المكفولين
        $query->has('sponsorships');
    } elseif ($status == 0) {
        // فقط الأيتام غير المكفولين
        $query->doesntHave('sponsorships');
    }
}

           $orphans = $query->orderBy('name', 'asc')->paginate(10)->appends($request->query());

        return view('orphans.index', compact('orphans'));
    }
    // صفحة اضافة يتيم
    public function create()
    {
        return view('orphans.create');
    }

    // حفظ يتيم جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:ذكر,أنثى',
            'address' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'child_image' => 'nullable|image|max:2048',
            'birth_certificate' => 'nullable|file|max:4096',
            'death_certificate' => 'nullable|file|max:4096',
            'guardian_id' => 'nullable|file|max:4096',
            'custody_document' => 'nullable|file|max:4096',
        ]);

        $data = $request->all();

        // رفع الملفات
        $files = ['child_image','birth_certificate','death_certificate','guardian_id','custody_document'];
        foreach($files as $file){
            if($request->hasFile($file)){
                $data[$file] = $request->file($file)->store('orphan/files', 'public');
            }
        }

        Orphan::create($data);

        return redirect()->route('orphans.index')->with('success','تم اضافة اليتيم بنجاح');
    }

    // عرض يتيم محدد
    public function show(Orphan $orphan)
    {
        return view('orphans.show', compact('orphan'));
    }

    // صفحة تعديل يتيم
    public function edit(Orphan $orphan)
    {
        return view('orphans.edit', compact('orphan'));
    }

  public function update(Request $request, Orphan $orphan)
{
    // التحقق من صحة البيانات
    $request->validate([
        'name'              => 'required|string|max:255',
        'email'             => 'required|email|unique:orphans,email,' . $orphan->id,
        'identity_number'   => 'required|string|unique:orphans,identity_number,' . $orphan->id,
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

    //  تجهيز البيانات الأساسية
    $data = $request->only([
        'name','email','identity_number','birthdate',
        'gender','bank_account','address','notes'
    ]);

    //  رفع صورة الطفل
    if ($request->hasFile('child_image')) {
        $data['child_image'] = $request->file('child_image')
            ->store('orphans', 'public'); 
    }

    //  رفع المستندات
    $documents = ['birth_certificate','guardian_id','custody_document','death_certificate'];
    foreach ($documents as $doc) {
        if ($request->hasFile($doc)) {
            $data[$doc] = $request->file($doc)
                ->store('documents', 'public'); 
        }
    }

    //  تحديث سجل اليتيم
    $orphan->update($data);

    return redirect()->route('orphans.dashboard')->with('success', 'تم تحديث البيانات بنجاح!');
}



    // حذف يتيم
    public function destroy(Orphan $orphan)
    {
        $orphan->delete();
        return redirect()->route('orphans.index')->with('success','تم حذف اليتيم');
    }

public function listOrphansForOrphan()
{
    $orphans = Orphan::all(); 
    return view('orphans.index', compact('orphans'));
}


// قائمة الأيتام في لوحة تحكم الكفيل
public function dashboard()
{
    $orphans = Orphan::orderBy('name', 'asc')->get();
    return view('sponsors.dashboard', compact('orphans'));
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

    if(!$orphan->isSponsored()) {
        $orphan->sponsorship()->create([
            'sponsor_id' => auth()->id(),
            'status' => 'active',
            'start_date' => now(),
        ]);
        return redirect()->route('sponsor.orphan.show', $id)
                         ->with('success', 'تم كفالة اليتيم بنجاح!');
    } else {
        return redirect()->route('sponsor.orphan.show', $id)
                         ->with('error', 'اليتيم مكفول بالفعل.');
    }
}


}

