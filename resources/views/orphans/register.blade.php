@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4 text-center">إنشاء حساب جديد لليتيم</h2>

    {{-- عرض الأخطاء --}}
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $dataRules = [
            'country' => 'فلسطين',
            'cities' => [
                'القدس' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني', 'بنك الاتحاد', 'البنك العربي الإسلامي'],
                'رام الله' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني', 'بنك الاتحاد'],
                'غزة' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
                'خانيونس' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
                'رفح' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
                'الوسطى' => ['بنك فلسطين', 'البنك الوطني', 'البنك الإسلامي الفلسطيني'],
                'الخليل' => ['بنك فلسطين', 'البنك الوطني'],
                'بيت لحم' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني'],
                'نابلس' => ['بنك فلسطين', 'بنك الاتحاد'],
                'جنين' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني'],
                'طولكرم' => ['بنك فلسطين', 'البنك العربي الإسلامي'],
                'قلقيلية' => ['بنك فلسطين', 'بنك الاتحاد'],
                'أريحا' => ['بنك فلسطين', 'البنك الإسلامي الفلسطيني'],
                'سلفيت' => ['بنك فلسطين', 'البنك العربي الفلسطيني']
            ]
        ];
    @endphp

    <form action="{{ route('orphans.register.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- الاسم، البريد الإلكتروني، رقم الهوية، تاريخ الميلاد --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">رقم الهوية</label>
                <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">تاريخ الميلاد</label>
                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
            </div>
        </div>

        {{-- الجنس، الدولة، المدينة، العنوان، الحالة التعليمية --}}
        <div class="row mb-3">
            <div class="col-md-2">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-select" required>
                    <option value="">اختر</option>
                    <option value="ذكر" {{ old('gender') == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                    <option value="أنثى" {{ old('gender') == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الدولة</label>
                <input type="text" name="country" class="form-control" value="فلسطين" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">المدينة</label>
                <select name="city" class="form-select" id="city-select" required>
                    <option value="">اختر المدينة</option>
                    @foreach(array_keys($dataRules['cities']) as $city)
                        <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">العنوان</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة التعليمية</label>
                <select name="education_status" class="form-select">
                    <option value="">اختر الحالة</option>
                    @foreach(['طفل','طالب','خريج','غير ملتحق'] as $status)
                        <option value="{{ $status }}" {{ old('education_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- رقم الحساب البنكي، اسم البنك، الملاحظات --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">رقم الحساب البنكي</label>
                <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">اسم البنك</label>
                <select name="bank_name" class="form-select" id="bank-select" required>
                    <option value="">اختر البنك</option>
                    @if(old('city') && isset($dataRules['cities'][old('city')]))
                        @foreach($dataRules['cities'][old('city')] as $bank)
                            <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- كلمة المرور وتأكيدها --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        {{-- رفع الملفات --}}
        <div class="row mb-3">
            @foreach(['child_image'=>'صورة الطفل','birth_certificate'=>'شهادة الميلاد','death_certificate'=>'شهادة الوفاة','guardian_id'=>'هوية ولي الأمر'] as $key=>$label)
                <div class="col-md-3">
                    <label class="form-label">{{ $label }}</label>
                    <input type="file" name="{{ $key }}" class="form-control">
                </div>
            @endforeach
        </div>

        {{-- زر التسجيل ورابط تسجيل الدخول --}}
        <div class="mb-3 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">تسجيل</button>
            <a href="{{ route('orphans.login') }}" class="btn btn-link">لديك حساب بالفعل؟ تسجيل الدخول</a>
        </div>
    </form>
</div>

<script>
    const cities = @json($dataRules['cities']);
    const citySelect = document.getElementById('city-select');
    const bankSelect = document.getElementById('bank-select');

    citySelect.addEventListener('change', function() {
        const city = this.value;
        bankSelect.innerHTML = '<option value="">اختر البنك</option>';
        if(city && cities[city]){
            cities[city].forEach(bank => {
                const option = document.createElement('option');
                option.value = bank;
                option.text = bank;
                bankSelect.appendChild(option);
            });
        }
    });
</script>

@endsection
