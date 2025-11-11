@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- عنوان لوحة تحكم الكفيل مع الأزرار في الأعلى --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2>لوحة تحكم الكفيل: {{ auth()->guard('sponsor')->user()->name }}</h2>
        <div class="d-flex gap-2 flex-wrap mt-2 mt-md-0">
            <a href="{{ route('sponsor.orphans.index') }}" class="btn btn-primary">عرض الكفالات الخاصة بي</a>
            <a href="{{ route('sponsor.edit') }}" class="btn btn-success">تعديل البيانات</a>
        </div>
    </div>

    {{-- جدول الأيتام مع فلتر البحث --}}
    <form method="GET" action="{{ route('sponsor.dashboard') }}">
        <table class="table table-bordered mt-2">
            <thead class="table-light">
                <tr>
                    <th>الاسم</th>
                    <th>العمر</th>
                    <th>الجنس</th>
                    <th>العنوان</th>
                    <th>حالة الكفالة</th>
                    <th>الإجراءات</th>
                </tr>

                {{-- فلتر البحث داخل الجدول --}}
                <tr>
                    <th>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="ابحث باسم اليتيم" value="{{ request('name') }}">
                    </th>
                    <th>
                        <div class="d-flex align-items-center gap-1">
                            <input type="number" name="age_from" class="form-control form-control-sm" placeholder="من" min="0" max="18" value="{{ request('age_from') }}">
                            <input type="number" name="age_to" class="form-control form-control-sm" placeholder="إلى" min="0" max="18" value="{{ request('age_to') }}">
                        </div>
                    </th>
                    <th>
                        <select name="gender" class="form-select form-select-sm">
                            <option value="">الكل</option>
                            <option value="ذكر" {{ request('gender') == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                            <option value="أنثى" {{ request('gender') == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </th>
                    <th>
                        <select name="city" class="form-select form-select-sm">
                            <option value="">اختر المدينة</option>
                            @php
                                $cities = [
                                    'القدس', 'رام الله', 'غزة', 'خانيونس', 'رفح', 'الوسطى',
                                    'الخليل', 'بيت لحم', 'نابلس', 'جنين', 'طولكرم', 'قلقيلية',
                                    'أريحا', 'سلفيت'
                                ];
                            @endphp
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </th>
                    <th>
                        <select name="sponsorship_status" class="form-select form-select-sm">
                            <option value="">حالة الكفالة</option>
                            <option value="sponsored" {{ request('sponsorship_status') == 'sponsored' ? 'selected' : '' }}>مكفول</option>
                            <option value="unsponsored" {{ request('sponsorship_status') == 'unsponsored' ? 'selected' : '' }}>غير مكفول</option>
                        </select>
                    </th>
                    <th>
                        <button type="submit" class="btn btn-primary btn-sm w-100">بحث</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($orphans as $orphan)
                    @php
                        $orphanAge = \Carbon\Carbon::parse($orphan->birthdate)->age ?? null;
                        $show = true;

                        // فلترة الاسم
                        $nameFilter = request('name');
                        if ($nameFilter && stripos($orphan->name, $nameFilter) === false) $show = false;

                        // فلترة العمر
                        $ageFrom = request('age_from');
                        $ageTo = request('age_to');
                        if ($ageFrom && $orphanAge < $ageFrom) $show = false;
                        if ($ageTo && $orphanAge > $ageTo) $show = false;

                        // فلترة الجنس
                        $genderFilter = request('gender');
                        if ($genderFilter && $orphan->gender != $genderFilter) $show = false;

                        // فلترة المدينة
                        $cityFilter = request('city');
                        if ($cityFilter && $orphan->city != $cityFilter) $show = false;

                        // فلترة حالة الكفالة
                        $statusFilter = request('sponsorship_status');
                        $hasSponsorship = $orphan->sponsorships->count() > 0;
                        if ($statusFilter == 'sponsored' && !$hasSponsorship) $show = false;
                        if ($statusFilter == 'unsponsored' && $hasSponsorship) $show = false;
                    @endphp

                    @if($show)
                    <tr>
                        <td>{{ $orphan->name }}</td>
                        <td>{{ $orphanAge ?? '-' }}</td>
                        <td>{{ $orphan->gender }}</td>
                        <td>{{ $orphan->city ?? $orphan->address }}</td>
                        <td>
                            @if($hasSponsorship)
                                <span class="badge bg-success">مكفول</span>
                            @else
                                <span class="badge bg-secondary">غير مكفول</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sponsor.orphan.show', $orphan->id) }}" class="btn btn-info btn-sm">عرض التفاصيل</a>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </form>
</div>
@endsection
