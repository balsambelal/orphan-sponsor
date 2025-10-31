<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orphan;
use App\Models\Sponsor;
use App\Models\Sponsorship;

class StatisticsController extends Controller
{
    public function index()
    {
        // إجمالي الأيتام
        $totalOrphans = Orphan::count();

        // إجمالي الكفلاء
        $totalSponsors = Sponsor::count();

        // إجمالي الكفالات (سجلات الكفالة)
        $totalSponsorships = Sponsorship::count();

        // عدد الأيتام المكفولين (عدد أيتام مميزين في جدول الكفالات)
        $sponsoredOrphans = Sponsorship::distinct('orphan_id')->count('orphan_id');

        // الأيتام غير المكفولين
        $unsponsoredOrphans = $totalOrphans - $sponsoredOrphans;

        // كفلات هذا الشهر
        $sponsoredThisMonth = Sponsorship::whereBetween('created_at', [
            now()->startOfMonth(), now()->endOfMonth()
        ])->count();

        return view('orphans.statistics', compact(
            'totalOrphans',
            'totalSponsors',
            'totalSponsorships',
            'sponsoredOrphans',
            'unsponsoredOrphans',
            'sponsoredThisMonth',
        ));
    }
}
