@extends('layouts.app')

@section('content')
<div class="container">
    <h2>لوحة تحكم الكفيل: {{ auth()->guard('sponsor')->user()->name }}</h2>
    <form method="POST" action="{{ route('orphans.logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">تسجيل خروج</button>
</form>


    <h4 class="mt-4">الأيتام</h4>
<!-- فلتر البحث -->
    <form method="GET" action="{{ route('sponsor.dashboard') }}" class="mb-3 row g-2 align-items-center">
        <div class="col-auto">
            <input type="text" name="name" class="form-control" placeholder="ابحث باسم اليتيم" value="{{ request('name') }}">
        </div>
        <div class="col-auto">
            <select name="sponsorship_status" class="form-select">
                <option value="">حالة الكفالة</option>
                <option value="sponsored" {{ request('sponsorship_status') == 'sponsored' ? 'selected' : '' }}>مكفول</option>
                <option value="unsponsored" {{ request('sponsorship_status') == 'unsponsored' ? 'selected' : '' }}>غير مكفول</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">فلترة</button>
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
    <a href="{{ route('sponsorships.index') }}" class="btn btn-primary">
        قائمة الكفالات
    </a>
    <a href="{{ route('sponsor.edit') }}" class="btn btn-warning">تعديل بيانات الكفيل</a>

</div>



@endsection

