@extends('layouts.app')

@section('content')
<div class="container-fluid py-3" style="height:100vh; overflow:hidden;">

    <div class="container h-100 d-flex flex-column justify-content-start overflow-auto">

        {{-- عنوان لوحة تحكم اليتيم مع الأزرار --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h2 class="fw-bold">لوحة تحكم اليتيم: {{ $orphan->name }}</h2>
            <div class="d-flex gap-2 flex-wrap mt-2 mt-md-0">
                <a href="{{ route('orphans.sponsorships', $orphan->id) }}" class="btn btn-primary">عرض الكفالات الخاصة بي</a>
                <a href="{{ route('orphans.edit', $orphan->id) }}" class="btn btn-success">تعديل البيانات</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- بطاقة بيانات اليتيم --}}
        <div class="card shadow-sm mb-3 flex-shrink-0">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0">معلومات اليتيم</h4>
            </div>
            <div class="card-body row">

                {{-- صورة الطفل --}}
                <div class="col-md-4 d-flex justify-content-center align-items-start mb-3">
                    @php
                        $childImage = $orphan->child_image;
                        $childFullPath = $childImage && file_exists(storage_path('app/public/'.$childImage))
                            ? asset('storage/'.$childImage)
                            : asset('images/default_orphan.png');
                    @endphp
                    <img src="{{ $childFullPath }}" alt="صورة الطفل"
                         class="img-fluid rounded shadow-sm"
                         width="200" height="auto"
                         style="object-fit:cover; border-radius:10px;">
                </div>

                {{-- بيانات اليتيم --}}
                <div class="col-md-8">
                    <div class="row">
                        @php
                            $info = [
                                'الاسم' => $orphan->name,
                                'البريد الإلكتروني' => $orphan->email,
                                'رقم الهوية' => $orphan->identity_number,
                                'تاريخ الميلاد' => $orphan->birthdate,
                                'الجنس' => $orphan->gender,
                                'اسم البنك' => $orphan->bank_name ?? '-',
                                'رقم الحساب البنكي' => $orphan->bank_account ?? '-',
                                'الدولة' => $orphan->country ?? '-',
                                'المدينة' => $orphan->city ?? '-',
                                'الحالة التعليمية' => $orphan->education_status ?? '-',
                                'العنوان' => $orphan->address,
                                'حالة الكفالة' => $orphan->is_sponsored ? 'مكفول' : 'غير مكفول',
                                'قيمة الكفالة' => $sponsorships->count() > 0 ? $sponsorships->first()->amount . ' دينار أردني' : 'غير مكفول',
                                'ملاحظات' => $orphan->notes ?? '-'
                            ];
                        @endphp

                        @foreach($info as $key => $value)
                            <div class="col-md-6 mb-2">
                                <strong>{{ $key }}:</strong> {{ $value }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- قسم المستندات --}}
        <div class="card shadow-sm flex-shrink-0">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">المستندات</h5>
            </div>
            <div class="row text-center p-3">
                @php
                    $documents = [
                        'birth_certificate' => 'شهادة الميلاد',
                        'guardian_id'       => 'هوية ولي الأمر',
                        'custody_document'  => 'شهادة الحضانة',
                        'death_certificate' => 'شهادة الوفاة'
                    ];
                @endphp

                @foreach($documents as $field => $label)
                    <div class="col-md-3 mb-3">
                        @if(!empty($orphan->$field))
                            @php
                                $docFile = $orphan->$field;
                                $docFullPath = file_exists(storage_path('app/public/'.$docFile))
                                    ? asset('storage/'.$docFile)
                                    : null;
                            @endphp
                            @if($docFullPath)
                                <a href="{{ $docFullPath }}" target="_blank">
                                    <img src="{{ $docFullPath }}"
                                         class="img-fluid rounded border"
                                         style="height:120px; object-fit:cover;">
                                </a>
                                <p class="mt-1">{{ $label }}</p>
                            @else
                                <div class="border rounded p-3 bg-light text-muted">غير متوفر</div>
                                <p class="mt-1">{{ $label }}</p>
                            @endif
                        @else
                            <div class="border rounded p-3 bg-light text-muted">غير متوفر</div>
                            <p class="mt-1">{{ $label }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
