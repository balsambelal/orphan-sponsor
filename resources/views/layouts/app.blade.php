<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>مشروع الأيتام</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">

    <style>
        body {
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            background-color: #ffb342;
        }

        body::before {
            content: "";
            background-image: url('{{ asset('images/welcome.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            opacity: 1;
            z-index: -1;
        }

        .nav-btn {
            margin: 0 4px;
            padding: 5px 12px;
            border-radius: 6px;
            font-weight: bold;
            color: #fff !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 38px;
        }

        .btn-green { background-color: #28a745 !important; }
        .btn-orange { background-color: #ffb342 !important; }

        .img-review:hover { transform: scale(1.5); transition: transform 0.3s; }
        td .btn { min-width: 120px; }
        td .btn-outline-info { min-width: auto; }
        .btn-warning { color: #fff; font-weight: bold; }
    </style>
</head>
<body>

    {{-- شريط التنقل --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('orphans.index') }}">الأيتام</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="تبديل التنقل">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex ms-auto">
                    <a href="{{ route('home') }}" class="nav-btn btn-green">الرئيسية</a>
                    <a href="{{ route('statistics') }}" class="nav-btn btn-orange">إحصائيات الموقع</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- محتوى الصفحة --}}
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- سكربت مخصص للوحة المدير فقط --}}
    @if(Request::is('admin/*'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // دالة لإظهار تنبيه مؤقت
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3 text-center w-50 shadow`;
            alert.textContent = message;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }

        // دالة لتبديل حالة التفعيل
        function toggleStatus(type, id, button) {
            fetch(`/admin/toggle-status/${type}/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const badge = document.getElementById(`status-${type}-${id}`);
                    const modalBadge = document.getElementById(`modal-status-${type}-${id}`);

                    [badge, modalBadge].forEach(b => {
                        if (b) {
                            b.textContent = data.is_active ? 'مفعل' : 'معطل';
                            b.className = 'badge ' + (data.is_active ? 'bg-success' : 'bg-danger');
                        }
                    });

                    button.classList.toggle('btn-success', !data.is_active);
                    button.classList.toggle('btn-danger', data.is_active);
                    button.textContent = data.is_active ? 'إلغاء التفعيل' : 'تفعيل';
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message || 'حدث خطأ', 'danger');
                }
            })
            .catch(() => showAlert('فشل الاتصال بالخادم', 'danger'));
        }

        // دالة لتبديل التوثيق
        function toggleVerify(type, id, button) {
            fetch(`/admin/toggle-verify/${type}/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const badge = document.getElementById(`verify-${type}-${id}`);
                    const modalBadge = document.getElementById(`modal-verify-${type}-${id}`);

                    [badge, modalBadge].forEach(b => {
                        if (b) {
                            b.textContent = data.is_verified ? 'تم التحقق' : 'لم يتم التحقق';
                            b.className = 'badge ' + (data.is_verified ? 'bg-success' : 'bg-secondary');
                        }
                    });

                    button.textContent = data.is_verified ? 'إلغاء التحقق' : 'توثيق البيانات';
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message || 'حدث خطأ', 'danger');
                }
            })
            .catch(() => showAlert('فشل الاتصال بالخادم', 'danger'));
        }

        // ربط الأزرار بالأحداث
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleStatus(btn.dataset.type, btn.dataset.id, btn));
        });

        document.querySelectorAll('.toggle-verify-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleVerify(btn.dataset.type, btn.dataset.id, btn));
        });

        // فتح المودال تلقائيًا عند وجود خطأ في إعادة تعيين كلمة المرور أو بيانات غير صحيحة
        @if(session('orphan_id') || session('sponsor_id') || $errors->any())
            const modalId = "{{ session('orphan_id') ?? session('sponsor_id') }}";
            const type = "{{ session('orphan_id') ? 'orphan' : 'sponsor' }}";
            if (modalId) {
                const modalEl = document.getElementById('modal' + type + modalId);
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            }
        @endif
    });
    </script>
    @endif

</body>
</html>

