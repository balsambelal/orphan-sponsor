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

public function createSponsorship($id)
{
    $orphan = Orphan::findOrFail($id);
    return view('sponsorships.create', compact('orphan'));
}

    public function create(Orphan $orphan)
    {
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

        Sponsorship::create([
            'orphan_id'  => $data['orphan_id'],
            'sponsor_id' => auth()->guard('sponsor')->id(),
            'amount'     => $data['amount'],
            'account_no' => $data['account_no'],
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'] ?? null,
            'status'     => 'active',
        ]);
        // تحديث حالة اليتيم في جدول الأيتام
        $orphan = Orphan::find($data['orphan_id']);
        $orphan->is_sponsored = 1;       // تأكد أن العمود موجود في جدول orphans
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