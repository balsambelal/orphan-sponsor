{{-- resources/views/orphans/register.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">إنشاء حساب جديد لليتيم</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orphans.register.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-2">
            <label>الاسم:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group mb-2">
            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group mb-2">
            <label>رقم الهوية:</label>
            <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}" required>
        </div>

        <div class="form-group mb-2">
            <label>كلمة المرور:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group mb-2">
            <label>تأكيد كلمة المرور:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group mb-2">
            <label>تاريخ الميلاد:</label>
            <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
        </div>

        <div class="form-group mb-2">
            <label>الجنس:</label>
            <select name="gender" class="form-control">
                <option value="">اختر</option>
                <option value="ذكر" {{ old('gender') == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                <option value="أنثى" {{ old('gender') == 'أنثى' ? 'selected' : '' }}>أنثى</option>
            </select>
        </div>

        <div class="form-group mb-2">
            <label>الحساب البنكي:</label>
            <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account') }}">
        </div>

        <div class="form-group mb-2">
            <label>العنوان:</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
        </div>

        <div class="form-group mb-2">
            <label>ملاحظات:</label>
            <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div class="form-group mb-2">
            <label>صورة الطفل:</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label>شهادة الميلاد:</label>
            <input type="file" name="birth_certificate" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label>شهادة الوفاة :</label>
            <input type="file" name="death_certificate" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label>هوية ولي الأمر:</label>
            <input type="file" name="guardian_id" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label>شهادة الحضانة:</label>
            <input type="file" name="custody_document" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">تسجيل</button>
    </form>

    <p class="mt-3">
        هل لديك حساب؟ <a href="{{ route('orphans.login') }}">تسجيل الدخول</a>
    </p>
</div>
@endsection
