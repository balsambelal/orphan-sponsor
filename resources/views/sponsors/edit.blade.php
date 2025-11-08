@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center mt-4">
    <div class="card p-4" style="max-width: 700px; width: 100%;">
        <div class="card-header text-center">
            <h3>تعديل بيانات الكفيل: {{ $sponsor->name }}</h3>
        </div>

        <div class="card-body">
            {{-- عرض رسالة النجاح --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- عرض الأخطاء --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sponsor.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- الاسم والبريد الإلكتروني --}}
                <div class="row gx-2 mb-3 align-items-end justify-content-center">
                    <div class="col-auto field-wrapper">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name', $sponsor->name) }}" required>
                    </div>
                    <div class="col-auto field-wrapper">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="{{ old('email', $sponsor->email) }}" required>
                    </div>
                </div>

                {{-- كلمة المرور وتأكيدها --}}
                <div class="row gx-2 mb-3 align-items-end justify-content-center">
                    <div class="col-auto field-wrapper">
                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="اترك الحقل فارغ إذا لم ترغب بتغيير كلمة المرور">
                    </div>
                    <div class="col-auto field-wrapper">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                               placeholder="أعد إدخال كلمة المرور الجديدة">
                    </div>
                </div>

                {{-- الحساب البنكي واسم البنك --}}
                <div class="row gx-2 mb-3 align-items-end justify-content-center">
                    <div class="col-auto field-wrapper">
                        <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
                        <input type="text" name="bank_account" id="bank_account" class="form-control"
                               value="{{ old('bank_account', $sponsor->bank_account) }}">
                    </div>
                    <div class="col-auto field-wrapper">
                        <label for="bank_name" class="form-label">اسم البنك</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control"
                               value="{{ old('bank_name', $sponsor->bank_name) }}">
                    </div>
                </div>

                {{-- الدولة والمدينة --}}
                <div class="row gx-2 mb-3 align-items-end justify-content-center">
                    <div class="col-auto field-wrapper">
                        <label for="country" class="form-label">الدولة</label>
                        <input type="text" name="country" id="country" class="form-control"
                               value="{{ old('country', $sponsor->country) }}">
                    </div>
                    <div class="col-auto field-wrapper">
                        <label for="city" class="form-label">المدينة</label>
                        <input type="text" name="city" id="city" class="form-control"
                               value="{{ old('city', $sponsor->city) }}">
                    </div>
                </div>

                {{-- أزرار التحكم --}}
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-primary">تحديث البيانات</button>
                    <a href="{{ route('sponsor.dashboard') }}" class="btn btn-secondary">رجوع للوحة التحكم</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- CSS لتنسيق الحقول بشكل مرتب --}}
<style>
    .field-wrapper input.form-control {
        max-width: 250px;
        width: 100%;
        font-size: 0.9rem;
        height: calc(1.5em + 0.5rem + 2px);
    }

    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    .row.gx-2 {
        column-gap: 0.5rem; /* تقليل المسافة بين الأعمدة */
        margin-bottom: 0.8rem;
    }

    .card {
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 10px;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.25rem;
    }
</style>
@endsection
