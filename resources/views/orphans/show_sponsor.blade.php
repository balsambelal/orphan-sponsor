@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- بطاقة اليتيم --}}
    <div class="card shadow-sm mb-4 p-3">
        <div class="row g-4 align-items-start">

            {{-- صورة الطفل --}}
            <div class="col-md-5 d-flex justify-content-center align-items-start">
                @php
                    $childImage = $orphan->child_image ?? null;
                    $childFullPath = $childImage && file_exists(storage_path('app/public/'.$childImage))
                        ? asset('storage/'.$childImage)
                        : asset('images/default_orphan.png');
                @endphp
                <img src="{{ $childFullPath }}"
                     alt="صورة الطفل"
                     class="img-fluid rounded shadow-sm"
                     style="width: 100%; max-width: 400px; height: auto; object-fit: cover;">
            </div>

            {{-- بيانات اليتيم --}}
            <div class="col-md-7">
                <div class="card-body p-3">

                    {{-- الاسم --}}
                    <h3 class="card-title mb-3">{{ $orphan->name }}</h3>

                    {{-- البيانات الأساسية --}}
                    <div class="row mb-2">
                        <div class="col-md-6 d-flex"><strong>العمر:</strong> <span class="ms-1">{{ \Carbon\Carbon::parse($orphan->birthdate)->age }} سنة</span></div>
                        <div class="col-md-6 d-flex"><strong>الجنس:</strong> <span class="ms-1">{{ $orphan->gender }}</span></div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6 d-flex"><strong>البريد الإلكتروني:</strong> <span class="ms-1">{{ $orphan->email }}</span></div>
                        <div class="col-md-6 d-flex"><strong>رقم الهوية:</strong> <span class="ms-1">{{ $orphan->identity_number }}</span></div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6 d-flex"><strong>العنوان:</strong> <span class="ms-1">{{ $orphan->address }}</span></div>
                        <div class="col-md-3 d-flex"><strong>الدولة:</strong> <span class="ms-1">{{ $orphan->country ?? '-' }}</span></div>
                        <div class="col-md-3 d-flex"><strong>المدينة:</strong> <span class="ms-1">{{ $orphan->city ?? '-' }}</span></div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6 d-flex"><strong>اسم البنك:</strong> <span class="ms-1">{{ $orphan->bank_name ?? '-' }}</span></div>
                        <div class="col-md-6 d-flex"><strong>رقم الحساب البنكي:</strong> <span class="ms-1">{{ $orphan->bank_account ?? '-' }}</span></div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6 d-flex"><strong>الحالة التعليمية:</strong> <span class="ms-1">{{ $orphan->education_status ?? '-' }}</span></div>
                        <div class="col-md-6 d-flex"><strong>ملاحظات:</strong> <span class="ms-1">{{ $orphan->notes ?? '-' }}</span></div>
                    </div>

                    {{-- المستندات على شكل عمودين --}}
                    <p class="mt-3 mb-2"><strong>المستندات:</strong></p>
                    <div class="row">
                        @php
                            $documents = [
                                'birth_certificate' => 'شهادة الميلاد',
                                'death_certificate' => 'شهادة وفاة',
                                'guardian_id'       => 'هوية الوصي',
                                'custody_document'  => 'وثيقة الحضانة',
                            ];
                        @endphp
                        @foreach($documents as $field => $label)
                            <div class="col-md-6 mb-2 d-flex align-items-center gap-1">
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

                        @if(!$alreadySponsoredByCurrent)
                            <a href="{{ route('sponsor.sponsorship.create', $orphan->id) }}" class="btn btn-success btn-sm">اكفلني</a>
                        @endif

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
    .btn-warning {
        color: #fff;
        font-weight: bold;
    }
    .img-fluid {
        max-width: 100%;
        height: auto;
        border-radius: 0.25rem;
    }
    .img-fluid:hover {
        transform: scale(1.03);
        transition: transform 0.3s;
    }
    .card-body strong {
        display: inline-block;
    }
    .card-body span {
        white-space: nowrap;
    }
</style>
@endsection

