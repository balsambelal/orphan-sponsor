@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تسجيل دخول الكفلاء</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('sponsor.login.post') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" name="password" class="form-control" id="password" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
        <a href="{{ route('sponsor.register') }}" class="btn btn-link">إنشاء حساب جديد</a>
    </form>
</div>
@endsection
