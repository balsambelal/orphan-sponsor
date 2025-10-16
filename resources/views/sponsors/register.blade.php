@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تسجيل حساب كفيل جديد</h2>

    {{-- عرض رسالة النجاح --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- عرض جميع الأخطاء Validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sponsor.register.post') }}">
        @csrf

        <!-- الاسم الكامل -->
        <div class="mb-3">
            <label for="name" class="form-label">الاسم الكامل</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- البريد الإلكتروني -->
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- اسم البنك -->
        <div class="mb-3">
            <label for="bank_name" class="form-label">اسم البنك</label>
            <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name') }}">
            @error('bank_name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- رقم الحساب البنكي -->
        <div class="mb-3">
            <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
            <input type="text" name="bank_account" id="bank_account" class="form-control" value="{{ old('bank_account') }}" placeholder="أدخل رقم الحساب البنكي">
            @error('bank_account') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- الدولة -->
        <div class="mb-3">
            <label for="country" class="form-label">الدولة</label>
            <input type="text" name="country" id="country" class="form-control" value="{{ old('country') }}">
            @error('country') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- المدينة -->
        <div class="mb-3">
            <label for="city" class="form-label">المدينة</label>
            <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
            @error('city') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- كلمة المرور -->
        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" name="password" class="form-control" id="password" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- تأكيد كلمة المرور -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
            @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- زر التسجيل -->
        <button type="submit" class="btn btn-primary">تسجيل</button>

        <!-- رابط تسجيل الدخول -->
        <a href="{{ route('sponsor.login') }}" class="btn btn-link">لديك حساب بالفعل؟ تسجيل الدخول</a>
    </form>
</div>
@endsection
