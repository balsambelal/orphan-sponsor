@extends('layouts.app')

@section('content')
<style>
    .overlay {
        background-color: rgba(255, 255, 255, 0.85); /* خلفية شفافة للنص */
        padding: 40px;
        border-radius: 10px;
        max-width: 600px;
        margin: 100px auto;
        text-align: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);

    }
</style>
<div class="overlay">
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

