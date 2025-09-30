@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>تعديل بيانات اليتيم: {{ $orphan->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('orphans.update', $orphan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- الاسم -->
            <div class="mb-3">
                <label class="form-label">اسم اليتيم</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $orphan->name) }}" required>
            </div>

            <!-- تاريخ الميلاد -->
            <div class="mb-3">
                <label class="form-label">تاريخ الميلاد</label>
                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $orphan->birthdate) }}" required>
            </div>

            <!-- الجنس -->
            <div class="mb-3">
                <label class="form-label">الجنس</label>
                <select name="gender" class="form-select">
                <option value="ذكر" {{ old('gender', $orphan->gender ?? '') == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                <option value="أنثى" {{ old('gender', $orphan->gender ?? '') == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                </select>

            </div>
            <div class="form-group">
    <label for="email">البريد الإلكتروني</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $orphan->email ?? '') }}">
</div>

<div class="form-group">
    <label for="identity_number">رقم الهوية</label>
    <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number', $orphan->identity_number ?? '') }}">
</div>


            <!-- العنوان -->
            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $orphan->address) }}">
            </div>
            <div class="mb-3">
                <label for="bank_account" class="form-label">رقم الحساب البنكي</label>
                <input type="text" name="bank_account" id="bank_account" class="form-control"
                 value="{{ old('bank_account', $orphan->bank_account ?? '') }}"
                 placeholder="أدخل رقم الحساب البنكي لليتيم">
            </div>

            <!-- الملاحظات -->
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control">{{ old('notes', $orphan->notes) }}</textarea>
            </div>

            <!-- رفع الملفات -->
            <div class="mb-3">
                <label class="form-label">صورة الطفل</label>
                <input type="file" name="child_image" class="form-control">
                @if($orphan->child_image)
                    <img src="{{ asset('storage/'.$orphan->child_image) }}" alt="Child Image" class="img-thumbnail mt-2" width="150">
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">شهادة الميلاد</label>
                <input type="file" name="birth_certificate" class="form-control">
                @if($orphan->birth_certificate)
                    <a href="{{ asset('storage/'.$orphan->birth_certificate) }}" target="_blank">عرض الملف الحالي</a>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">شهادة الوفاة</label>
                <input type="file" name="death_certificate" class="form-control">
                @if($orphan->death_certificate)
                    <a href="{{ asset('storage/'.$orphan->death_certificate) }}" target="_blank">عرض الملف الحالي</a>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">هوية ولي الأمر</label>
                <input type="file" name="guardian_id" class="form-control">
                @if($orphan->guardian_id)
                    <a href="{{ asset('storage/'.$orphan->guardian_id) }}" target="_blank">عرض الملف الحالي</a>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">وثيقة الحضانة</label>
                <input type="file" name="custody_document" class="form-control">
                @if($orphan->custody_document)
                    <a href="{{ asset('storage/'.$orphan->custody_document) }}" target="_blank">عرض الملف الحالي</a>
                @endif
            </div>

            <!-- زر الحفظ -->
            <button type="submit" class="btn btn-success">تحديث بيانات اليتيم</button>
            <a href="{{ route('orphans.dashboard') }}" class="btn btn-secondary">رجوع  </a>
        </form>
    </div>
</div>
@endsection
