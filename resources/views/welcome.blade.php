@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>مرحباً بك في مشروع الأيتام</h1>
    <p>اختر طريقة الدخول:</p>

    <div class="d-flex justify-content-center gap-3 mt-4">
        <a href="{{ route('orphans.login') }}" class="btn btn-primary">
         دخول كيتيم / إنشاء حساب
        </a>
        <a href="{{ route('sponsor.login') }}" class="btn btn-success btn-lg">دخول كفيل</a>
    </div>
</div>
@endsection

