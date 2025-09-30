@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تسجيل حساب كفيل جديد</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('sponsor.register.post') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">الاسم الكامل</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
           <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
           <input type="text" name="bank_account" id="bank_account" class="form-control"
           value="{{ old('bank_account') }}" placeholder="أدخل رقم الحساب البنكي">
       </div>


        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" name="password" class="form-control" id="password" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">تسجيل</button>
        <a href="{{ route('sponsor.login') }}" class="btn btn-link">لديك حساب بالفعل؟ تسجيل الدخول</a>
    </form>
</div>
@endsection


