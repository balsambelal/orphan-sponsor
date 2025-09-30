@extends('layouts.app')

@section('content')
<div class="container">
    <h4>معلومات الطفل</h4>
<p>اسم الطفل: {{ $orphan->name }}</p>
<p>رقم الحساب البنكي: {{ $orphan->bank_account ?? 'غير محدد' }}</p>
<p>تاريخ الميلاد: {{ $orphan->birthdate }}</p>

<h4>معلومات الكفيل</h4>
<p>اسم الكفيل: {{ Auth::guard('sponsor')->user()->name }}</p>
<p>رقم الحساب البنكي للكفيل: {{ Auth::guard('sponsor')->user()->bank_account }}</p>

<h4>بيانات الكفالة</h4>
<form action="{{ route('sponsor.sponsorship.store', $orphan->id) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="amount">قيمة الكفالة</label>
        <input type="number" name="amount" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="start_date">تاريخ البداية</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="end_date">تاريخ النهاية</label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
     <div class="mt-3">
    <button type="submit" class="btn btn-success">تسجيل الكفالة</button>
    <a href="{{ route('sponsor.dashboard') }}" class="btn btn-secondary">رجوع</a>
    </div>

</form>

</div>
@endsection
