@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>تعديل بيانات اليتيم: {{ $orphan->name }}</h3>
    </div>

    <div class="card-body">

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

        <form action="{{ route('orphans.update', $orphan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- الاسم والبريد الإلكتروني ورقم الهوية --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">اسم اليتيم</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $orphan->name) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $orphan->email) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">رقم الهوية</label>
                    <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number', $orphan->identity_number) }}" required>
                </div>
            </div>

            {{-- تاريخ الميلاد والجنس --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">تاريخ الميلاد</label>
                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $orphan->birthdate) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-select" required>
                        <option value="ذكر" {{ old('gender', $orphan->gender) == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                        <option value="أنثى" {{ old('gender', $orphan->gender) == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
            </div>

            {{-- الدولة، المدينة، اسم البنك --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">الدولة</label>
                    <select name="country" class="form-select" required>
                        <option value="">اختر الدولة</option>
                        <option value="فلسطين" {{ old('country', $orphan->country) == 'فلسطين' ? 'selected' : '' }}>فلسطين</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">المدينة</label>
                    <select name="city" class="form-select" id="city-select" required>
                        <option value="">اختر المدينة</option>
                        @foreach(array_keys($dataRules['cities']) as $city)
                            <option value="{{ $city }}" {{ old('city', $orphan->city) == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">اسم البنك</label>
                    <select name="bank_name" class="form-select" id="bank-select" required>
                        <option value="">اختر البنك</option>
                        @php
                            $currentCity = old('city', $orphan->city);
                        @endphp
                        @if($currentCity && isset($dataRules['cities'][$currentCity]))
                            @foreach($dataRules['cities'][$currentCity] as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name', $orphan->bank_name) == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            {{-- رقم الحساب البنكي والحالة التعليمية --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">رقم الحساب البنكي</label>
                    <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account', $orphan->bank_account) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الحالة التعليمية</label>
                    <select name="education_status" class="form-select" required>
                        <option value="">اختر الحالة</option>
                        @foreach(['طفل','طالب','خريج','غير ملتحق'] as $status)
                            <option value="{{ $status }}" {{ old('education_status', $orphan->education_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- العنوان والملاحظات --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $orphan->address) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control">{{ old('notes', $orphan->notes) }}</textarea>
                </div>
            </div>

            {{-- رفع الملفات --}}
            @foreach(['child_image','birth_certificate','death_certificate','guardian_id','custody_document'] as $doc)
                <div class="mb-3">
                    <label class="form-label">{{ ucfirst(str_replace('_',' ',$doc)) }}</label>
                    <input type="file" name="{{ $doc }}" class="form-control">
                    @if(!empty($orphan->$doc))
                        <p class="mt-2">
                            <a href="{{ asset('storage/'.$orphan->$doc) }}" target="_blank">عرض الملف الحالي</a>
                        </p>
                        @if(str_contains($doc,'image'))
                            <img src="{{ asset('storage/'.$orphan->$doc) }}" alt="{{ $doc }}" class="img-thumbnail" width="150">
                        @endif
                    @endif
                </div>
            @endforeach

            {{-- كلمة المرور --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">كلمة المرور الجديدة</label>
                    <input type="password" class="form-control" name="password" placeholder="اتركه فارغًا إن لم ترغب بالتغيير">
                </div>
                <div class="col-md-6">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>
            </div>

            {{-- أزرار --}}
            <button type="submit" class="btn btn-success">تحديث بيانات اليتيم</button>
            <a href="{{ route('orphans.dashboard') }}" class="btn btn-secondary">رجوع</a>
        </form>
    </div>
</div>

{{-- جافاسكربت لتحديث قائمة البنوك عند تغيير المدينة --}}
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

