<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use Illuminate\Http\Request;



class SponsorshipController extends Controller
{
public function index()
{
    $sponsorships = Sponsorship::with(['orphan', 'sponsor'])
        ->orderBy('start_date', 'desc')
        ->paginate(10);

    return view('sponsorships.index', compact('sponsorships'));
}

public function createSponsorship($orphanId)
{
    $orphan = Orphan::findOrFail($orphanId);
    $currentSponsor = auth()->guard('sponsor')->user();

    // تحقق هل الطفل مكفول من نفس الكفيل
    $alreadySponsoredByCurrent = $orphan->sponsors()->where('sponsor_id', $currentSponsor->id)->exists();

    if ($alreadySponsoredByCurrent) {
        return redirect()->route('sponsor.dashboard')
                         ->with('error', 'لقد كفلت هذا اليتيم بالفعل.');
    }

    // السماح بالكفالة حتى لو الطفل مكفول من كفيل آخر
    return view('sponsorships.create', compact('orphan'));
}


public function store(Request $request)
{
    $data = $request->validate([
        'orphan_id'  => 'required|exists:orphans,id',
        'amount'     => 'required|numeric|min:0',
        'account_no' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ]);

    $orphan = Orphan::findOrFail($data['orphan_id']);
    $sponsorId = auth()->guard('sponsor')->id();

    // تحقق إذا الكفيل نفسه حاول كفالة الطفل مسبقًا
    if ($orphan->sponsorships()->where('sponsor_id', $sponsorId)->exists()) {
        return redirect()->back()->with('error', 'لقد قمت بكفالة هذا الطفل مسبقًا.');
    }

    // إنشاء الكفالة الجديدة، حتى لو كان الطفل مكفول من كفيل آخر
    Sponsorship::create([
        'orphan_id'  => $orphan->id,
        'sponsor_id' => $sponsorId,
        'amount'     => $data['amount'],
        'account_no' => $data['account_no'],
        'start_date' => $data['start_date'],
        'end_date'   => $data['end_date'] ?? null,
        'status'     => 'active',
    ]);

    // تحديث حالة اليتيم للعرض في لوحة المدير
    $orphan->is_sponsored = 1;
    $orphan->save();

    return redirect()->route('sponsor.dashboard')
                     ->with('success','تم تسجيل الكفالة بنجاح');
}


    public function show(Sponsorship $sponsorship)
    {
        return view('sponsorships.show', compact('sponsorship'));
    }

    public function edit(Sponsorship $sponsorship)
    {
        return view('sponsorships.edit', compact('sponsorship'));
    }

    public function update(Request $request, Sponsorship $sponsorship)
    {
        $sponsorship->update($request->all());
        return redirect()->route('sponsorships.index');
    }

    public function destroy(Sponsorship $sponsorship)
    {
        $sponsorship->delete();
        return redirect()->route('sponsorships.index');
    }
}