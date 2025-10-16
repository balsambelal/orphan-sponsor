@extends('layouts.app')

@section('content')
<div class="container">
<div class="mb-3">
    <form method="POST" action="{{ route('sponsor.logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">
            تسجيل خروج
        </button>
    </form>
</div>
    <h2>لوحة تحكم الكفيل: {{ auth()->guard('sponsor')->user()->name }}</h2>
    <form method="POST" action="{{ route('sponsor.logout') }}">
    @csrf
</form>


    <h4 class="mt-4">الأيتام</h4>
<!-- فلتر البحث -->
<form method="GET" action="{{ route('sponsor.dashboard') }}" class="mb-3 row g-2 align-items-center">

    <!-- بحث باسم اليتيم -->
    <div class="col-auto">
        <input type="text" name="name" class="form-control" placeholder="ابحث باسم اليتيم" value="{{ request('name') }}">
    </div>

    <!-- بحث بالعمر -->
    <div class="col-auto">
        <select name="age" class="form-select">
           <option value="" {{ request('age') === null || request('age') === '' ? 'selected' : '' }}>اختر العمر</option>
           @for ($i = 0; $i <= 18; $i++)
           <option value="{{ $i }}" {{ (string)request('age') === (string)$i ? 'selected' : '' }}>{{ $i }} سنة</option>
           @endfor
       </select>

    </div>

    <!-- بحث بالحالة التعليمية -->
    <div class="col-auto">
        <select name="education_status" class="form-select">
            <option value="">الحالة التعليمية</option>
            <option value="طفل" {{ request('education_status') == 'طفل' ? 'selected' : '' }}>طفل</option>
            <option value="طالب" {{ request('education_status') == 'طالب' ? 'selected' : '' }}>  طالب</option>
            <option value="غير ملتحق" {{ request('education_status') == 'ملتحق غير ' ? 'selected' : '' }}>غير ملتحق</option>
            <option value="خريج" {{ request('education_status') == ' خريج' ? 'selected' : '' }}>خريج </option>

        </select>
    </div>

    <!-- بحث بالمدينة -->
    <div class="col-auto">
        <select name="city" class="form-select">
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
    </div>

    <!-- حالة الكفالة -->
    <div class="col-auto">
        <select name="sponsorship_status" class="form-select">
            <option value="">حالة الكفالة</option>
            <option value="sponsored" {{ request('sponsorship_status') == 'sponsored' ? 'selected' : '' }}>مكفول</option>
            <option value="unsponsored" {{ request('sponsorship_status') == 'unsponsored' ? 'selected' : '' }}>غير مكفول</option>
        </select>
    </div>

    <!-- زر البحث -->
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">بحث</button>
    </div>
</form>


    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>العمر</th>
                <th>الجنس</th>
                <th>العنوان</th>
                <th>حالة الكفالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orphans as $orphan)
                <tr>
                    <td>{{ $orphan->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($orphan->birthdate)->age ?? '-' }}</td>
                    <td>{{ $orphan->gender }}</td>
                    <td>{{ $orphan->address }}</td>
 <td>
        @if($orphan->sponsorships->count() > 0)
            <span class="badge bg-success">مكفول</span>
        @else
            <span class="badge bg-secondary">غير مكفول</span>
        @endif
    </td>
                    <td>
                        <a href="{{ route('sponsor.orphan.show', $orphan->id) }}" class="btn btn-info btn-sm">عرض التفاصيل</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="col-md-6 d-flex justify-content-end gap-2 mb-3">
    <div class="col-auto">
        <a href="{{ route('sponsor.orphans.index') }}" 
           class="btn btn-primary">
           عرض الكفالات الخاصة بي
        </a>
    </div>

    <div class="col-auto">
        <a href="{{ route('sponsor.edit') }}" 
           class="btn btn-success">
           تعديل بيانات الكفيل
        </a>
    </div>
</div>


@endsection

