@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- بطاقة اليتيم --}}
    <div class="card shadow-sm mb-4 p-3">
        <div class="row g-3 align-items-start">

            {{-- صورة الطفل --}}
            <div class="col-md-4 d-flex justify-content-center">
                <div style="width:100%; max-width:250px;">
                    <img src="{{ !empty($orphan->child_image) ? asset('storage/'.$orphan->child_image) : 'https://via.placeholder.com/300x400?text=No+Image' }}"
                         alt="صورة الطفل"
                         class="img-fluid rounded shadow-sm"
                         style="width:100%; height:auto; max-height:350px; object-fit:cover;">
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
                    <p class="mb-1"><strong>رقم الحساب البنكي:</strong> {{ $orphan->bank_account ?? '-' }}</p>
                    <p class="mb-1"><strong>ملاحظات:</strong> {{ $orphan->notes ?? '-' }}</p>

                    {{-- المستندات بشكل رأسي --}}
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

                    {{-- الأزرار --}}
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <a href="{{ route('sponsor.dashboard') }}" class="btn btn-secondary btn-sm">رجوع</a>

                        @if($isSponsor)
                            @if(!$orphan->isSponsored())
                                <a href="{{ route('sponsor.sponsorship.create', $orphan->id) }}"
                                   class="btn btn-success btn-sm">اكفلني</a>
                            @else
                                <span class="badge bg-success align-self-center">تم كفالة هذا اليتيم بالفعل</span>
                            @endif
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection

