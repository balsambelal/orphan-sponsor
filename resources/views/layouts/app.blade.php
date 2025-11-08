<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>مشروع رعاية</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Droid+Arabic+Kufi&display=swap" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Droid Arabic Kufi', sans-serif !important;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            direction: rtl;
            text-align: right;
            background-color: #ffd782;
            position: relative;
        }

        body::before {
            content: "";
            background-image: url('{{ asset('images/welcome.jpg') }}');
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

        .navbar-brand img { height: 45px; margin-left: 10px; border-radius: 50%; }
        .navbar-brand span { font-size: 1.8rem; font-weight: bold; color: #fff !important; }

        .navbar-custom { background-color: #FFA500 !important; }
        .navbar-custom .nav-btn { color: #fff !important; }

        /* حاوية المحتوى تدعم الدفع للفوتر */
        .content-wrapper {
            flex: 1 0 auto;
            width: 100%;
        }

        /* ---------------- Footer ---------------- */
        .welcome-footer {
            background-color: #FFA500;
            width: 100%;
            padding: 15px 0;
            text-align: center;
            color: #fff;
            font-weight: bold;
            letter-spacing: 1px;
            flex-shrink: 0; /* لا يتقلص */
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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex ms-auto align-items-center flex-wrap gap-2">

                    <a href="{{ route('home') }}" class="nav-btn btn-green">الرئيسية</a>
                    <a href="{{ route('about') }}" class="nav-btn btn-orange">عن الموقع</a>
                    <a href="{{ route('terms') }}" class="nav-btn btn-orange">السياسات والشروط</a>
                    <a href="{{ route('contact') }}" class="nav-btn btn-orange">تواصل معنا</a>
                    <a href="{{ route('statistics') }}" class="nav-btn btn-orange">إحصائيات الموقع</a>

                    @php
                        if (Request::is('orphans/*')) {
                            $logoutRoute = route('orphans.login');
                        } elseif (Request::is('sponsor/*')) {
                            $logoutRoute = route('sponsor.login');
                        } elseif (Request::is('admin/*')) {
                            $logoutRoute = route('admin.login');
                        } else {
                            $logoutRoute = route('home');
                        }
                    @endphp

                    <a href="{{ $logoutRoute }}" class="nav-btn btn-red">تسجيل الخروج</a>

                </div>
            </div>

        </div>
    </nav>

    {{-- محتوى الصفحة --}}
    <div class="content-wrapper container py-4">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="welcome-footer">
        مشروع رعاية © {{ date('Y') }} - جميع الحقوق محفوظة
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
        });
    </script>
    @endif

</body>
</html>

