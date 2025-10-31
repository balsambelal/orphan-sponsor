@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">تعديل بيانات الكفيل</h2>

    {{-- عرض رسالة النجاح --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- عرض الأخطاء إن وجدت --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sponsor.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name', $sponsor->name) }}" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="{{ old('email', $sponsor->email) }}" required>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="اترك الحقل فارغ إذا لم ترغب بتغيير كلمة المرور">
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                       placeholder="أعد إدخال كلمة المرور الجديدة">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="country" class="form-label">الدولة</label>
                <input type="text" name="country" id="country" class="form-control"
                       value="{{ old('country', $sponsor->country) }}">
            </div>
            <div class="col-md-6">
                <label for="city" class="form-label">المدينة</label>
                <input type="text" name="city" id="city" class="form-control"
                       value="{{ old('city', $sponsor->city) }}">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
                <input type="text" name="bank_account" id="bank_account" class="form-control"
                       value="{{ old('bank_account', $sponsor->bank_account) }}">
            </div>
            <div class="col-md-6">
                <label for="bank_name" class="form-label">اسم البنك</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control"
                       value="{{ old('bank_name', $sponsor->bank_name) }}">
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">تحديث البيانات</button>
            <a href="{{ route('sponsor.dashboard') }}" class="btn btn-secondary">رجوع للوحة التحكم</a>
        </div>
    </form>
</div>
@endsection
