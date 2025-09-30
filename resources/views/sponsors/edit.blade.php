@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">تعديل بيانات الكفيل</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('sponsor.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">الاسم</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name', $sponsor->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email', $sponsor->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
            <input type="text" name="bank_account" id="bank_account" class="form-control"
                   value="{{ old('bank_account', $sponsor->bank_account) }}">
        </div>

        <button type="submit" class="btn btn-primary">تحديث البيانات</button>
        <a href="{{ route('sponsor.dashboard') }}" class="btn btn-secondary">رجوع للوحة التحكم</a>
    </form>
</div>
@endsection
