@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- رأس الصفحة --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-center flex-grow-1">لوحة تحكم المدير</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">تسجيل الخروج</button>
        </form>
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
        ];
    @endphp

    @foreach($sections as $title => $section)
        <h3 class="mt-5 mb-3 text-primary">{{ $title }}</h3>

        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>الاسم</th>
                    <th>عرض البيانات</th>
                    <th>الحالة</th>
                    <th>توثيق البيانات</th>
                    <th>الإجراءات</th>
                    @if($section['type'] == 'orphan')<th>الكفالات</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($section['data'] as $item)
                    <tr>
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
                                <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                        @if($section['type'] == 'orphan')
                            <td><a href="{{ route('admin.orphan.sponsorships', $item->id) }}" 
                                   class="btn btn-info btn-sm">عرض الكفالات</a></td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">لا يوجد بيانات حالياً</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- مودالات --}}
        @foreach($section['data'] as $item)
            <div class="modal fade" id="modal{{ $section['type'] }}{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
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
                                             class="img-fluid img-review" 
                                             style="max-height:400px; cursor:zoom-in;">
                                    @else
                                        <p>لا توجد صورة شخصية</p>
                                    @endif
                                </div>
                                <div class="col-md-6 text-center">
                                    <h6>المستندات</h6>
                                    @foreach(['birth_certificate','death_certificate','custody_document','documents'] as $doc)
                                        @if(isset($item->$doc) && $item->$doc)
                                            <a href="{{ asset('storage/' . $item->$doc) }}" target="_blank" 
                                               class="btn btn-outline-info mb-2">
                                               {{ ucfirst(str_replace('_',' ',$doc)) }}
                                            </a><br>
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
                                @foreach($item->getAttributes() as $key => $value)
                                    @if(!in_array($key, ['id','photo','child_image','documents','birth_certificate','death_certificate','custody_document','password','is_active','is_verified','created_at','updated_at','guardian_id']))
                                        <div class="col-md-4"><strong>{{ ucfirst(str_replace('_',' ',$key)) }}:</strong> {{ $value }}</div>
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

    @endforeach
</div>

{{-- CSS إضافي --}}
<style>
.img-review:hover { transform: scale(1.5); transition: transform 0.3s; }
td .btn { min-width: 120px; }
td .btn-outline-info { min-width: auto; }
.btn-warning { color: #fff; font-weight: bold; }
</style>

@endsection
