@extends('layouts.app')

@section('content')
<div class="container py-3">

    {{-- رأس الصفحة --}}
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
        <h1 class="fw-bold text-center flex-grow-1" style="margin-top: 0.5rem; margin-bottom: 0.5rem;">لوحة تحكم المدير</h1>
    </div>

    {{-- رسائل التنبيه --}}
    @foreach (['success','error','password_success','password_error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ str_contains($msg,'error') ? 'danger' : 'success' }}">{{ session($msg) }}</div>
        @endif
    @endforeach

    @php
        $sections = [
            'الأيتام' => ['data' => $orphans, 'type' => 'orphan'],
            'الكفلاء' => ['data' => $sponsors, 'type' => 'sponsor'],
            'الكفالات' => ['data' => $sponsorships, 'type' => 'sponsorship'],
        ];
    @endphp

    {{-- البحث العام --}}
    <div class="mb-2 d-flex justify-content-center">
        <label for="filterSection" class="fw-bold me-2 align-self-center" style="font-size:1.4rem; color:#000;">بحث:</label>
        <select id="filterSection" class="form-select text-center" style="width:230px; font-size:1.05rem; padding:0.3rem 0.6rem;">
            <option value="all" selected>عرض الكل</option>
            <option value="orphan">الأيتام</option>
            <option value="sponsor">الكفلاء</option>
            <option value="sponsorship">الكفالات</option>
        </select>
    </div>

    {{-- الأقسام --}}
    @foreach($sections as $title => $section)
        <div class="section-wrapper" data-section="{{ $section['type'] }}">
            <h3 class="mt-3 mb-3 text-primary border-bottom pb-2">{{ $title }}</h3>

            <table class="table table-bordered table-striped align-middle text-center shadow-sm">
                <thead class="table-dark">
                    <tr>
                        @if($section['type'] == 'sponsorship')
                            <th>اسم اليتيم</th>
                            <th>اسم الكفيل</th>
                            <th>المبلغ</th>
                            <th>تاريخ بداية الكفالة</th>
                            <th>تاريخ نهاية الكفالة</th>
                        @else
                            <th>الاسم</th>
                            <th>عرض البيانات</th>
                            <th>الحالة</th>
                            <th>توثيق البيانات</th>
                            <th>الإجراءات</th>
                        @endif
                    </tr>

                    {{-- صف الفلاتر لكل عمود --}}
                    <tr class="table-light">
                        @if($section['type'] == 'sponsorship')
                            <th><input type="text" class="form-control form-control-sm filter-input" placeholder="بحث عن اليتيم" data-col="0"></th>
                            <th><input type="text" class="form-control form-control-sm filter-input" placeholder="بحث عن الكفيل" data-col="1"></th>
                            <th><input type="text" class="form-control form-control-sm filter-input" placeholder="بحث عن المبلغ" data-col="2"></th>
                            <th><input type="text" class="form-control form-control-sm filter-input" placeholder="بحث عن تاريخ البداية" data-col="3"></th>
                            <th><input type="text" class="form-control form-control-sm filter-input" placeholder="بحث عن تاريخ النهاية" data-col="4"></th>
                        @else
                            <th><input type="text" class="form-control form-control-sm filter-input" placeholder="بحث عن الاسم" data-col="0"></th>
                            <th></th>
                            <th>
                                <select class="form-select form-select-sm filter-select" data-col="2">
                                    <option value="">الحالة: الكل</option>
                                    <option value="مفعل">مفعل</option>
                                    <option value="معطل">معطل</option>
                                </select>
                            </th>
                            <th>
                                <select class="form-select form-select-sm filter-select" data-col="3">
                                    <option value="">توثيق البيانات: الكل</option>
                                    <option value="تم التحقق">تم التحقق</option>
                                    <option value="لم يتم التحقق">لم يتم التحقق</option>
                                </select>
                            </th>
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($section['data'] as $item)
                        <tr>
                            @if($section['type'] == 'sponsorship')
                                <td>{{ $item->orphan->name ?? '-' }}</td>
                                <td>{{ $item->sponsor->name ?? '-' }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}</td>
                            @else
                                <td>{{ $item->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal{{ $section['type'] }}{{ $item->id }}">
                                        مراجعة البيانات
                                    </button>
                                </td>
                                <td>
                                    <span id="status-{{ $section['type'] }}-{{ $item->id }}" 
                                          class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                          {{ $item->is_active ? 'مفعل' : 'معطل' }}
                                    </span>
                                </td>
                                <td>
                                    <span id="verify-{{ $section['type'] }}-{{ $item->id }}" 
                                          class="badge {{ $item->is_verified ? 'bg-success' : 'bg-secondary' }}">
                                          {{ $item->is_verified ? 'تم التحقق' : 'لم يتم التحقق' }}
                                    </span>
                                </td>
                                <td class="d-flex justify-content-center gap-2">
                                    <button type="button"
                                            class="btn btn-sm toggle-status-btn {{ $item->is_active ? 'btn-danger' : 'btn-success' }}"
                                            data-type="{{ $section['type'] }}" 
                                            data-id="{{ $item->id }}">
                                        {{ $item->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-dark toggle-verify-btn"
                                            data-type="{{ $section['type'] }}" 
                                            data-id="{{ $item->id }}">
                                        {{ $item->is_verified ? 'إلغاء التحقق' : 'توثيق البيانات' }}
                                    </button>

                                    <form action="{{ route('admin.deleteUser', ['type'=>$section['type'], 'id'=>$item->id]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">لا يوجد بيانات حالياً</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- مودالات الأيتام والكفلاء --}}
        @if($section['type'] != 'sponsorship')
            @foreach($section['data'] as $item)
                <div class="modal fade" id="modal{{ $section['type'] }}{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">مراجعة بيانات {{ $item->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                {{-- الصور والمستندات --}}
                                <div class="row mb-3">
                                    <div class="col-md-6 text-center">
                                        <h6>الصورة الشخصية</h6>
                                        @if($item->child_image ?? $item->photo)
                                            <img src="{{ asset('storage/' . ($item->child_image ?? $item->photo)) }}" 
                                                 class="img-fluid img-review border rounded shadow" 
                                                 style="max-height:400px; cursor:zoom-in;">
                                        @else
                                            <p>لا توجد صورة شخصية</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <h6>المستندات</h6>
                                        @foreach(['birth_certificate'=>'شهادة الميلاد','death_certificate'=>'شهادة الوفاة','custody_document'=>'وثيقة الحضانة','documents'=>'المستندات الأخرى'] as $doc => $arabName)
                                            @if(isset($item->$doc) && $item->$doc)
                                                <a href="{{ asset('storage/' . $item->$doc) }}" target="_blank" 
                                                   class="btn btn-outline-info mb-2">{{ $arabName }}</a><br>
                                            @endif
                                        @endforeach
                                        @if(!($item->birth_certificate || $item->death_certificate || $item->custody_document || $item->documents))
                                            <p>لا توجد مستندات</p>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                {{-- بيانات المستخدم --}}
                                <div class="row mb-2">
                                    @php
                                        $translations = [
                                            'name' => 'الاسم',
                                            'email' => 'البريد الإلكتروني',
                                            'phone' => 'رقم الهاتف',
                                            'gender' => 'الجنس',
                                            'birthdate' => 'تاريخ الميلاد',
                                            'address' => 'العنوان',
                                            'country' => 'الدولة',
                                            'city' => 'المدينة',
                                            'bank_account' => 'الحساب البنكي',
                                            'bank_name' => 'اسم البنك',
                                            'notes' => 'ملاحظات',
                                            'identity_number' => 'رقم الهوية',
                                            'sponsorships_count' => 'عدد الكفالات',
                                            'is_sponsored' => 'حالة الكفالة',
                                            'education_status' => 'الحالة التعليمية'
                                        ];
                                    @endphp

                                    @foreach($item->getAttributes() as $key => $value)
                                        @if(!in_array($key, ['id','photo','child_image','documents','birth_certificate','death_certificate','custody_document','password','is_active','is_verified','created_at','updated_at','guardian_id']))
                                            <div class="col-md-4">
                                                <strong>{{ $translations[$key] ?? ucfirst(str_replace('_',' ',$key)) }}:</strong> 
                                                @if($key == 'birth_date' && $value)
                                                    {{ \Carbon\Carbon::parse($value)->format('d-m-Y') }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <hr>

                                {{-- إعادة تعيين كلمة المرور --}}
                                <h6>إعادة تعيين كلمة المرور:</h6>
                                <form action="{{ $section['type']=='orphan' ? route('admin.orphans.forceResetOrphanPassword',$item->id) : route('admin.sponsors.forceResetSponsorPassword',$item->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="كلمة المرور الجديدة" required>
                                        <input type="password" name="password_confirmation" class="form-control mt-2" placeholder="تأكيد كلمة المرور" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">إعادة التعيين</button>

                                    @if(session('orphan_id') == $item->id || session('sponsor_id') == $item->id)
                                        @if(session('password_success'))
                                            <div class="alert alert-success mt-2">{{ session('password_success') }}</div>
                                        @elseif(session('password_error'))
                                            <div class="alert alert-danger mt-2">{{ session('password_error') }}</div>
                                        @endif
                                    @endif
                                </form>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endforeach
</div>

{{-- CSS إضافي --}}
<style>
.img-review:hover { transform: scale(1.5); transition: transform 0.3s; }
td .btn { min-width: 120px; }
td .btn-outline-info { min-width: auto; }
.btn-warning { color: #fff; font-weight: bold; }
</style>

{{-- فلترة الجداول --}}
<script>
const filterSelect = document.getElementById('filterSection');

filterSelect.addEventListener('change', function() {
    const selected = this.value;
    document.querySelectorAll('.section-wrapper').forEach(section => {
        section.style.display = (selected === 'all' || section.dataset.section === selected) ? 'block' : 'none';
    });
});

// فلترة الأعمدة لكل جدول
document.querySelectorAll('.table').forEach(table => {
    const rows = Array.from(table.querySelectorAll('tbody tr'));

    function applyFilters() {
        const inputs = Array.from(table.querySelectorAll('.filter-input'));
        const selects = Array.from(table.querySelectorAll('.filter-select'));

        rows.forEach(row => {
            let show = true;

            // فلترة نصية لكل input
            inputs.forEach(input => {
                const col = input.dataset.col;
                const value = input.value.trim().toLowerCase();
                const td = row.cells[col];
                if(td && !td.textContent.toLowerCase().includes(value)) show = false;
            });

            // فلترة select لكل select
            selects.forEach(select => {
                const col = select.dataset.col;
                const val = select.value.trim().toLowerCase();
                if(val) {
                    const td = row.cells[col];
                    if(td) {
                        const span = td.querySelector('span');
                        const text = span ? span.textContent.trim().toLowerCase() : td.textContent.trim().toLowerCase();
                        if(text !== val) show = false;
                    }
                }
            });

            row.style.display = show ? '' : 'none';
        });
    }

    table.querySelectorAll('.filter-input').forEach(i => i.addEventListener('keyup', applyFilters));
    table.querySelectorAll('.filter-select').forEach(s => s.addEventListener('change', applyFilters));
});
</script>
@endsection
