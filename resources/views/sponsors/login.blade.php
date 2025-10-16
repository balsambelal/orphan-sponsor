@extends('layouts.app')

@section('content')
<div class="container" style="max-width:400px">
    <h3 class="mb-4 text-center">تسجيل دخول الكفلاء</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sponsor.login.post') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password">كلمة المرور</label>
            <input type="password" name="password" class="form-control" id="password" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
    </form>

    <hr>
    <p class="text-center mt-3">ليس لديك حساب؟</p>
    <a href="{{ route('sponsor.register') }}" class="btn btn-success w-100">إنشاء حساب كفيل جديد</a>
</div>
@endsection

