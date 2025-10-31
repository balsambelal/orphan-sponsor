@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #ff9800;
        background-image: url('{{ asset("images/orange_background.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        font-family: Arial, sans-serif;
        overflow-y: auto; /* ✅ تم تعديلها لتفعيل السكرول */
    }

    .overlay {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 25px;
        border-radius: 15px;
        max-width: 700px;
        margin: 20px auto;
        text-align: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .overlay h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .main-sections {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
        margin: 10px auto;
        max-width: 1400px;
        padding: 10px 20px;
    }

    .section {
        flex: 1 1 700px;
        padding: 25px 20px;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    .section h2 {
        text-align: center;
        color: #d84315;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .activity-card {
        border: none;
        border-radius: 12px;
        padding: 20px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .activity-card:hover {
        transform: translateY(-5px);
    }

    .activity-card h4 {
        color: #ff5722;
        margin-bottom: 10px;
    }

    .activity-card p {
        font-size: 15px;
        line-height: 1.6;
        color: #555;
    }

    .btn-orange {
        background-color: #ff9800;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
    }

    .btn-orange:hover {
        background-color: #fb8c00;
    }

    .btn-admin {
        background-color: #d84315;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
    }

    .btn-admin:hover {
        background-color: #bf360c;
    }

    /* ===== تنسيق عرض الأنشطة + القصة بجانب بعض ===== */
    .activities-story {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: stretch;
        justify-content: center;
    }

    .activities {
        flex: 1 1 55%;
    }

    .story-section {
        flex: 1 1 40%;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 25px;
    }

    .story-title {
        text-align: center;
        color: #d84315;
        font-size: 26px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .story-image {
        background-image: url('/images/images.jpg');
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        height: 220px;
        margin-bottom: 15px;
        filter: grayscale(100%);
    }

    .story-text {
        font-size: 17px;
        color: #555;
        line-height: 1.6;
        text-align: justify;
    }

    @media (max-width: 991px) {
        .activities-story {
            flex-direction: column;
        }

        .story-image {
            height: 200px;
        }
    }
</style>

<div class="overlay">
    <h1>مرحباً بك في مشروع رعاية</h1>
    <p>اختر طريقة الدخول:</p>
    <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
        <a href="{{ route('orphans.login') }}" class="btn btn-primary btn-lg">دخول كيتيم</a>
        <a href="{{ route('sponsor.login') }}" class="btn btn-orange btn-lg">دخول كفيل</a>
        <a href="{{ route('admin.login') }}" class="btn btn-admin btn-lg">دخول المدير</a>
    </div>
</div>

<div class="main-sections">
    <div class="section container">
        <h2>أنشطة المشروع </h2>

        <div class="activities-story">
            {{-- قسم الأنشطة --}}
            <div class="activities">
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="activity-card">
                            <img src="{{ asset('images/activity1.png') }}" alt="كفالة الأيتام" class="img-fluid mb-3" style="height:130px;">
                            <h4>كفالة الأيتام</h4>
                            <p>نوفر دعمًا ماليًا ومعنويًا للأطفال الأيتام لضمان تعليمهم وحياتهم الكريمة.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="activity-card">
                            <img src="{{ asset('images/activity2.png') }}" alt="التعليم والدعم النفسي" class="img-fluid mb-3" style="height:130px;">
                            <h4>التعليم والدعم النفسي</h4>
                            <p>نوفر برامج تعليمية ودورات تدريبية، بالإضافة إلى جلسات دعم نفسي للأطفال الأيتام.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="activity-card">
                            <img src="{{ asset('images/activity3.png') }}" alt="التكافل المجتمعي" class="img-fluid mb-3" style="height:130px;">
                            <h4>التكافل المجتمعي</h4>
                            <p>نشجع المجتمع على المشاركة والمساهمة في تحسين حياة الأيتام من خلال الحملات والمبادرات.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- قسم قصة اليتيم --}}
            <div class="story-section">
                <h1 class="story-title">قصة الطفل اليتيم</h1>
                <div class="story-image"></div>
                <p class="story-text">
                    كل طفل يتيم يحمل قصة مؤثرة، قصة فقدان الأمان والحب.
                    هدفنا في هذا المشروع أن نكون يدًا تمتد لهؤلاء الأطفال،
                    لنمنحهم الأمل ونبني لهم مستقبلًا أفضل، مليئًا بالعطاء والرعاية.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
