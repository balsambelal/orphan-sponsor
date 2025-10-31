@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">تسجيل حساب كفيل جديد</h2>

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

        {{-- الاسم والبريد الإلكتروني --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">الاسم الكامل</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- كلمة المرور وتأكيدها --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" id="password" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- الدولة والمدينة --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="country" class="form-label">الدولة</label>
                <input type="text" name="country" id="country" class="form-control" value="{{ old('country') }}">
                @error('country') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label for="city" class="form-label">المدينة</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
                @error('city') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- رقم الحساب واسم البنك --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
                <input type="text" name="bank_account" id="bank_account" class="form-control" value="{{ old('bank_account') }}" placeholder="أدخل رقم الحساب البنكي">
                @error('bank_account') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label for="bank_name" class="form-label">اسم البنك</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name') }}">
                @error('bank_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- زر التسجيل ورابط تسجيل الدخول --}}
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">تسجيل</button>
            <a href="{{ route('sponsor.login') }}" class="btn btn-link">لديك حساب بالفعل؟ تسجيل الدخول</a>
        </div>
    </form>
</div>
@endsection
