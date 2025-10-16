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
    }

    .overlay {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 40px;
        border-radius: 15px;
        max-width: 700px;
        margin: 50px auto;
        text-align: center;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    .section {
        padding: 40px 20px;
        background-color: rgba(255, 255, 255, 0.95);
        margin: 40px auto;
        border-radius: 15px;
        max-width: 1000px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
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

    .story-section {
        display: flex;
        justify-content: center;
        padding: 50px 20px;
    }

    .content {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 15px;
        max-width: 900px;
        width: 100%;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .story-title {
        text-align: center;
        color: #d84315;
        font-size: 36px;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .story-content {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
    }

    .story-image {
        flex: 1 1 300px;
        background-image: url('/images/images.jpg');
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        min-height: 300px;
        filter: grayscale(100%);
    }

    .story-content p {
        flex: 2 1 400px;
        font-size: 24px;
        color: #555;
        line-height: 1.6;
    }

    @media (max-width: 991px) {
        .story-image {
            display: none;
        }
    }
</style>

<div class="overlay">
    <h1>مرحباً بك في مشروع الأيتام</h1>
    <p>اختر طريقة الدخول:</p>
    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
        <a href="{{ route('orphans.login') }}" class="btn btn-primary btn-lg">دخول كيتيم / إنشاء حساب</a>
        <a href="{{ route('sponsor.login') }}" class="btn btn-orange btn-lg">دخول كفيل</a>
        <a href="{{ route('admin.login') }}" class="btn btn-admin btn-lg">دخول المدير</a>
    </div>
</div>

{{-- قسم أنشطة المشروع --}}
<div class="section container">
    <h2 class="text-center">أنشطة المشروع</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="activity-card">
                <img src="{{ asset('images/activity1.png') }}" alt="كفالة الأيتام" class="img-fluid mb-3" style="height:150px;">
                <h4>كفالة الأيتام</h4>
                <p>نوفر دعمًا ماليًا ومعنويًا للأطفال الأيتام لضمان تعليمهم وحياتهم الكريمة.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="activity-card">
                <img src="{{ asset('images/activity2.png') }}" alt="التعليم والدعم النفسي" class="img-fluid mb-3" style="height:150px;">
                <h4>التعليم والدعم النفسي</h4>
                <p>نوفر برامج تعليمية ودورات تدريبية، بالإضافة إلى جلسات دعم نفسي للأطفال الأيتام.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="activity-card">
                <img src="{{ asset('images/activity3.png') }}" alt="التكافل المجتمعي" class="img-fluid mb-3" style="height:150px;">
                <h4>التكافل المجتمعي</h4>
                <p>نشجع المجتمع على المشاركة والمساهمة في تحسين حياة الأيتام من خلال الحملات والمبادرات.</p>
            </div>
        </div>
    </div>
</div>

{{-- قسم قصة الطفل اليتيم --}}
<div class="story-section">
    <div class="content">
        <h1 class="story-title">قصة الطفل اليتيم</h1>
        <div class="story-content">
            <div class="story-image"></div>
            <p>
                كل طفل يتيم يحمل قصة مؤثرة، قصة فقدان وفقدان الأمان والحب.
                هدفنا في هذا المشروع أن نكون يدًا تمتد لهؤلاء الأطفال،
                لنمنحهم الأمل، ونبني لهم مستقبلًا أفضل.
            </p>
        </div>
    </div>
</div>

@endsection
