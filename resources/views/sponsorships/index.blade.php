@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row mb-4">
        <div class="col-md-6">
            <h3>قائمة الكفالات</h3>
        </div>
        <div class="col-md-3 text-end">
        <a href="{{ route('sponsor.dashboard') }}" class="btn btn-secondary w-100">رجوع إلى لوحة التحكم</a>
        </div>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>#</th>
                <th>اسم اليتيم</th>
                <th>اسم الكفيل</th>
                <th>قيمة الكفالة</th>
                <th>تاريخ البداية</th>
                <th>تاريخ النهاية</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sponsorships as $index => $sponsorship)
                <tr class="text-center">
                    <td>{{ $index + $sponsorships->firstItem() }}</td>
                    <td>{{ $sponsorship->orphan->name }}</td>
                    <td>{{ $sponsorship->sponsor->name }}</td>
                    <td>{{ number_format($sponsorship->amount, 2) }} د.أ</td>
                    <td>{{ \Carbon\Carbon::parse($sponsorship->start_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sponsorship->end_date)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">لا توجد كفالات</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</div>
@endsection

