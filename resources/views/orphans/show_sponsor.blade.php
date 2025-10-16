@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- بطاقة اليتيم --}}
    <div class="card shadow-sm mb-4 p-3">
        <div class="row g-3 align-items-start">

            {{-- صورة الطفل --}}
            <div class="col-md-4 d-flex justify-content-center align-items-start mb-3">
                @php
                    $childImage = $orphan->child_image ?? null;
                    $childFullPath = $childImage && file_exists(storage_path('app/public/'.$childImage))
                        ? asset('storage/'.$childImage)
                        : asset('images/default_orphan.png');
                @endphp

                <div style="width:100%; max-width:300px;">
                    <img src="{{ $childFullPath }}"
                    alt="صورة الطفل"
                    class="img-fluid rounded shadow-sm"
                    style="width:100%; height:auto; object-fit:cover;">
                </div>
            </div>

            {{-- بيانات اليتيم --}}
            <div class="col-md-8">
                <div class="card-body p-2">
                    <h3 class="card-title">{{ $orphan->name }}</h3>
                    <p class="mb-1"><strong>العمر:</strong> {{ \Carbon\Carbon::parse($orphan->birthdate)->age }} سنة</p>
                    <p class="mb-1"><strong>الجنس:</strong> {{ $orphan->gender }}</p>
                    <p class="mb-1"><strong>البريد الإلكتروني:</strong> {{ $orphan->email }}</p>
                    <p class="mb-1"><strong>رقم الهوية:</strong> {{ $orphan->identity_number }}</p>
                    <p class="mb-1"><strong>العنوان:</strong> {{ $orphan->address }}</p>
                    <p class="mb-1"><strong>الدولة:</strong> {{ $orphan->country ?? '-' }}</p>
                    <p class="mb-1"><strong>المدينة:</strong> {{ $orphan->city ?? '-' }}</p>
                    <p class="mb-1"><strong>اسم البنك:</strong> {{ $orphan->bank_name ?? '-' }}</p>
                    <p class="mb-1"><strong>رقم الحساب البنكي:</strong> {{ $orphan->bank_account ?? '-' }}</p>
                    <p class="mb-1"><strong>الحالة التعليمية:</strong> {{ $orphan->education_status ?? '-' }}</p>
                    <p class="mb-1"><strong>ملاحظات:</strong> {{ $orphan->notes ?? '-' }}</p>

                    {{-- المستندات --}}
                    <p class="mt-3 mb-2"><strong>المستندات:</strong></p>
                    <div class="d-flex flex-column gap-2">
                        @php
                            $documents = [
                                'birth_certificate' => 'شهادة الميلاد',
                                'death_certificate' => 'شهادة وفاة',
                                'guardian_id'       => 'هوية الوصي',
                                'custody_document'  => 'وثيقة الحضانة',
                            ];
                        @endphp

                        @foreach($documents as $field => $label)
                            <div class="d-flex align-items-center gap-2">
                                <strong>{{ $label }}:</strong>
                                @if(!empty($orphan->$field))
                                    <a href="{{ asset('storage/'.$orphan->$field) }}" target="_blank"
                                       class="btn btn-outline-primary btn-sm py-0 px-2">
                                        عرض
                                    </a>
                                @else
                                    <span class="text-muted">غير متوفر</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- رسائل التنبيه --}}
                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif

                    {{-- أزرار الكفالة والتحذيرات --}}
                    @php
                        $currentSponsor = auth()->guard('sponsor')->id();
                        $alreadySponsoredByCurrent = $orphan->sponsors()->where('sponsor_id', $currentSponsor)->exists();
                        $alreadySponsoredByOthers = $orphan->sponsors()->where('sponsor_id', '!=', $currentSponsor)->exists();
                    @endphp

                    <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                        <a href="{{ route('sponsor.dashboard') }}" class="btn btn-secondary btn-sm">رجوع</a>

                        {{-- زر الكفالة يظهر فقط إذا الكفيل لم يكفله مسبقًا --}}
                        @if(!$alreadySponsoredByCurrent)
                            <a href="{{ route('sponsor.sponsorship.create', $orphan->id) }}" class="btn btn-success btn-sm">اكفلني</a>
                        @endif

                        {{-- رسائل التحذير --}}
                        @if($alreadySponsoredByCurrent)
                            <span class="badge bg-success ms-2">لقد كفلت هذا اليتيم بالفعل</span>
                        @elseif($alreadySponsoredByOthers)
                            <span class="badge bg-warning ms-2">الطفل مكفول من كفيل آخر</span>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

{{-- تحسين المظهر --}}
<style>
    td .btn {
        min-width: 120px;
    }
    td .btn-outline-info {
        min-width: auto;
    }
    .btn-warning {
        color: #fff;
        font-weight: bold;
    }
    .img-review:hover {
        transform: scale(1.5);
        transition: transform 0.3s;
    }
</style>
@endsection
