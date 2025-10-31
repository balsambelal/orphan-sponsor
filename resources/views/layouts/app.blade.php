<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>مشروع رعاية</title>

    <!-- Bootstrap CSS RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">

    <!-- خط Droid Arabic Kufi -->
    <link href="https://fonts.googleapis.com/css2?family=Droid+Arabic+Kufi&display=swap" rel="stylesheet">

    <style>
        /* توحيد الخطوط */
        body, h1, h2, h3, h4, h5, h6, p, input, select, textarea, button, table {
            font-family: 'Droid Arabic Kufi', sans-serif !important;
        }

        body {
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            background-color: #ffd782;
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
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            transition: all 0.3s ease;
        }

        .btn-green { background-color: #28a745 !important; color: #fff !important; }
        .btn-orange { background-color: #ffb342 !important; color: #fff !important; font-weight: bold; }
        .btn-red { background-color: #dc3545 !important; color: #fff !important; }

        .navbar-brand img {
            height: 45px;
            margin-left: 10px;
            border-radius: 50%;
        }

        /* تكبير نص "رعاية" */
        .navbar-brand span {
            font-size: 1.8rem;
            font-weight: bold;
            color: #fff !important;
        }

        .img-review:hover { transform: scale(1.5); transition: transform 0.3s; }
        td .btn { min-width: 120px; }
        td .btn-outline-info { min-width: auto; }
        .btn-warning { color: #fff; font-weight: bold; }

        /* تعديل لون النافبار */
        .navbar-custom {
            background-color: #FFA500 !important; /* برتقالي دافئ */
        }

        .navbar-custom .nav-btn {
            color: #fff !important;
        }

        /* جعل نص إحصائيات الموقع أبيض دائمًا */
        .navbar-custom .btn-orange {
            color: #fff !important;
            background-color: #ffb342 !important;
            border: none;
        }

        .navbar-custom .btn-orange:hover {
            background-color: #ff9e1f !important;
            color: #fff !important;
        }
    </style>
</head>
<body>

    {{-- شريط التنقل --}}
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('orphans.index') }}">
                <img src="{{ asset('images/raya.png') }}" alt="شعار رعاية">
                <span>رعــــــايــة</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="تبديل التنقل">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex ms-auto align-items-center">
                    <!-- زر الإحصائيات -->
                    <a href="{{ route('statistics') }}" class="nav-btn btn-orange">إحصائيات الموقع</a>

                    <!-- زر الرئيسية يظهر فقط في صفحات login و register لليتيم والكفيل، وصفحة الإحصائيات -->
                    @php
                        $currentRoute = Route::currentRouteName();
                        $homeRoutes = [
                            'orphans.login', 'sponsor.login', 'admin.login',
                            'orphans.register', 'sponsor.register',
                            'statistics'
                        ];
                        $showHome = in_array($currentRoute, $homeRoutes);
                    @endphp

                    @if($showHome)
                        <a href="{{ route('home') }}" class="nav-btn btn-green">الرئيسية</a>
                    @endif

                    <!-- زر تسجيل الخروج يظهر فقط إذا لم تكن الصفحة Login أو Register -->
                    @php
                        $excludedRoutes = [
                            'orphans.login', 'orphans.register',
                            'sponsor.login', 'sponsor.register',
                            'admin.login', 'admin.register'
                        ];
                        $showLogout = !in_array($currentRoute, $excludedRoutes);
                        $logoutRoute = '';
                        if ($showLogout) {
                            if (Request::is('orphans/*')) {
                                $logoutRoute = route('orphans.login');
                            } elseif (Request::is('sponsor/*')) {
                                $logoutRoute = route('sponsor.login');
                            } elseif (Request::is('admin/*')) {
                                $logoutRoute = route('admin.login');
                            }
                        }
                    @endphp

                    @if($logoutRoute)
                        <a href="{{ $logoutRoute }}" class="nav-btn btn-red">تسجيل الخروج</a>
                    @endif
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
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3 text-center w-50 shadow`;
            alert.textContent = message;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }

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

        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleStatus(btn.dataset.type, btn.dataset.id, btn));
        });

        document.querySelectorAll('.toggle-verify-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleVerify(btn.dataset.type, btn.dataset.id, btn));
        });

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
