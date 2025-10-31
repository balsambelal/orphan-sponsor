            @extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px; max-width: 400px;">
    <div class="card shadow">
        <div class="card-header text-center" style="background-color: #000; color: white;">
            <h3>تسجيل دخول المدير</h3>
        </div>
        <div class="card-body">
            @if(session('error'))
            
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-dark w-100 fw-bold">دخول</button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" style="color: #000; text-decoration: none; font-weight: bold;">
                    ← العودة للصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

