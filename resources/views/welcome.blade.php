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
        overflow-y: auto;
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
        flex-direction: column;
        gap: 25px;
        margin: 20px auto;
        max-width: 1400px;
        padding: 10px 20px;
    }

    .section {
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
        margin-bottom: 20px;
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
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 25px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 20px;
    }

    .story-image {
        flex: 1 1 300px;
        height: 220px;
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        filter: grayscale(0%);
    }

    .story-content {
        flex: 2 1 400px;
    }

    .story-title {
        color: #d84315;
        font-size: 26px;
        margin-bottom: 15px;
        font-weight: bold;
        text-align: center;
    }

    .story-text {
        font-size: 17px;
        color: #555;
        line-height: 1.6;
        text-align: justify;
    }

    @media (max-width: 991px) {
        .story-section {
            flex-direction: column;
        }
        .story-image {
            width: 100%;
            height: 200px;
        }
        .story-content {
            width: 100%;
        }
        .story-title {
            text-align: center;
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
    {{-- أنشطة المشروع --}}
    {{-- أنشطة المشروع --}}
<div class="section">
    <h2>أنشطة المشروع</h2>
    <div class="row g-4 justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="activity-card text-center">
                <img src="{{ asset('images/activity1.png') }}" alt="كفالة الأيتام" class="img-fluid mb-3" style="height:130px;">
                <h4> 🏠❤️ كفالة الأيتام</h4>
                <p>نوفر دعمًا ماليًا ومعنويًا للأطفال الأيتام لضمان تعليمهم وحياتهم الكريمة.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="activity-card text-center">
                <img src="{{ asset('images/activity2.png') }}" alt="التعليم والدعم النفسي" class="img-fluid mb-3" style="height:130px;">
                <h4> ✏️📚 التعليم والدعم النفسي </h4>
                <p>نوفر برامج تعليمية ودورات تدريبية، بالإضافة إلى جلسات دعم نفسي للأطفال الأيتام.</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="activity-card text-center">
                <img src="{{ asset('images/activity3.png') }}" alt="التكافل المجتمعي" class="img-fluid mb-3" style="height:130px;">
                <h4>🤝🫂التكافل المجتمعي</h4>
                <p>نشجع المجتمع على المشاركة والمساهمة في تحسين حياة الأيتام من خلال الحملات والمبادرات.</p>
            </div>
        </div>
    </div>
</div>


   {{-- قصة الطفل اليتيم --}}
<div class="story-section">
    <div class="story-image" style="background-image: url('{{ asset('images/orphan_story.jpg') }}');"></div>
    <div class="story-content">
        <h2 class="story-title">قصة الطفل اليتيم</h2>
        <p class="story-text" style="font-weight:bold; color:#d84315; font-size:18px; margin-bottom:15px;">
            "كنت خائفًا من المستقبل، حتى جاء من يؤمن بي."
        </p>
        <p class="story-text">
            آدم، أحد أطفال مشروع رعاية، بدأ حياته مليئة بالتحديات بعد فقدان والده.  
            بفضل الكفالة والدعم النفسي والتعليم، أصبح لديه أمل وحياة أفضل.  
            مشروع رعاية يغير حياة الأطفال خطوة بخطوة، ليمنحهم الأمان والتعليم والفرصة لبناء مستقبل مشرق.
        </p>
    </div>
</div>

{{-- قصة الكفيل --}}
<div class="story-section">
    <div class="story-image" style="background-image: url('{{ asset('images/sponsor_story.jpg') }}');"></div>
    <div class="story-content">
        <h2 class="story-title">قصة الكفيل</h2>
        <p class="story-text">
             الشكر لكفلاء رعاية الذين حملوا رسالة العطاء على عاتقهم، وساهموا في تغيير حياة الأطفال الأيتام. 
            من خلال دعمهم المستمر، أصبح للأطفال فرصة للتعلم والنمو في بيئة آمنة، واكتساب مهارات الحياة، 
            وبناء مستقبل مشرق مليء بالأمل والطموح. كل كفيل هنا يترك بصمة إيجابية لا تُنسى في حياة طفل محتاج.
        </p>
    </div>
</div>

{{-- قالوا عنا --}}
<div class="story-section">
    <div class="story-content">
        <h2 class="story-title">قالوا عنا</h2>
        <p class="story-text">
            "بفضل مشروع رعاية، شعرت بالأمان والأمل من جديد. لقد أعادوا لي الفرحة التي فقدتها، وأكدوا لي أن هناك من يهتم بي."    <br><br>
            
            "الكفالة هنا ليست مجرد دعم مالي، بل فرصة حقيقية لتغيير حياة طفل. رؤية طفولي يبتسمون كل يوم هي أعظم مكافأة يمكن أن أحصل عليها."   <br><br>
            
            "فريق العمل ملتزم ومخلص، يجعل العطاء رحلة ممتعة وملهمة. شعرت أن مساهمتي تُحدث فرقًا حقيقيًا في حياة الأطفال."   <br><br>
            
            "كل يوم نرى قصص نجاح جديدة، أطفال يتعلمون وينمون، ويشعرون بالأمل. هذا المشروع حقًا يزرع الحب والعطاء في كل قلب."   
        </p>
    </div>
</div>

</div>
@endsection
