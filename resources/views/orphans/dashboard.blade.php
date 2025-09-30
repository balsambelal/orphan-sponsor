@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">لوحة تحكم اليتيم</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- بطاقة بيانات اليتيم --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>معلومات اليتيم</h4>
        </div>
        <div class="card-body row">

            {{-- صورة الطفل --}}
<div class="col-md-4 d-flex justify-content-center align-items-start mb-3">
    @php
        $childImage = $orphan->child_image;
        $childFullPath = $childImage && file_exists(storage_path('app/public/'.$childImage))
            ? asset('storage/'.$childImage)
            : 'https://via.placeholder.com/300x400?text=No+Image';
    @endphp
    <div style="width:100%; max-width:300px;">
        <img src="{{ $childFullPath }}"
             alt="صورة الطفل"
             class="img-fluid rounded shadow-sm"
             style="width:100%; height:auto; max-height:400px; object-fit:cover;">
    </div>
</div>


            {{-- بيانات اليتيم --}}
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr><th>الاسم:</th><td>{{ $orphan->name }}</td></tr>
                    <tr><th>البريد الإلكتروني:</th><td>{{ $orphan->email }}</td></tr>
                    <tr><th>رقم الهوية:</th><td>{{ $orphan->identity_number }}</td></tr>
                    <tr><th>تاريخ الميلاد:</th><td>{{ $orphan->birthdate }}</td></tr>
                    <tr><th>الجنس:</th><td>{{ $orphan->gender }}</td></tr>
                    <tr><th>الحساب البنكي:</th><td>{{ $orphan->bank_account ?? '-' }}</td></tr>
                    <tr><th>العنوان:</th><td>{{ $orphan->address }}</td></tr>
                    <tr><th>حالة الكفالة:</th>
                        <td>{{ $orphan->is_sponsored ? 'مكفول' : 'غير مكفول' }}</td></tr>
                    <tr>
                        <th>قيمة الكفالة:</th>
                        <td>
                            @if($sponsorship)
                                {{ $sponsorship->amount }} دينار أردني
                            @else
                                غير مكفول
                            @endif
                        </td>
                    </tr>
                    <tr><th>ملاحظات:</th><td>{{ $orphan->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- قسم المستندات --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5>المستندات</h5>
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
                @if(!empty($orphan->$field))
                    @php
                        $docFile = $orphan->$field;
                        $docFullPath = file_exists(storage_path('app/public/'.$docFile))
                            ? asset('storage/'.$docFile)
                            : null;
                    @endphp
                    <div class="col-md-3 mb-3">
                        @if($docFullPath)
                            <a href="{{ $docFullPath }}" target="_blank">
                                <img src="{{ $docFullPath }}"
                                     class="img-fluid rounded border"
                                     style="height:150px;object-fit:cover;">
                            </a>
                            <p class="mt-1">{{ $label }}</p>
                        @else
                            <div class="border rounded p-4 bg-light text-muted">
                                غير متوفر
                            </div>
                            <p class="mt-1">{{ $label }}</p>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- الأزرار --}}
    <div class="text-center mt-4">
        <a href="{{ route('orphans.edit', $orphan->id) }}" class="btn btn-warning mx-2">تعديل البيانات</a>
        <a href="{{ route('orphans.list') }}" class="btn btn-info mx-2">تصفح الأيتام</a>
        <form action="{{ route('orphans.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger mx-2">تسجيل الخروج</button>
        </form>
    </div>
</div>
@endsection


