@extends('layouts.app')
@section('content')
<div class="container" style="max-width:400px">
    <h3 class="mb-4 text-center">تسجيل دخول اليتيم</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orphans.login.submit') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="form-group mb-3">
            <label for="password">كلمة المرور</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
    </form>

    <hr>
    <p class="text-center mt-3">ليس لديك حساب؟</p>
    <a href="{{ route('orphans.register') }}" class="btn btn-success w-100">إنشاء حساب يتيم جديد</a>
</div>
@endsection

