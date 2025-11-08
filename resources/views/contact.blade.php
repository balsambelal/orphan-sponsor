@extends('layouts.app')

@section('content')
<div class="overlay" style="max-width:1000px; padding:40px 30px; margin:30px auto; background-color:#ffffff; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
    <h1 style="color:#d84315; font-size:2.5rem; margin-bottom:30px; text-align:center;">تواصل معنا</h1>
    
    <p style="font-size:18px; line-height:1.9; text-align:right; color:#000000;">
        نحن هنا للإجابة على استفساراتكم ومساعدتكم في كل ما يتعلق بمشروع <strong>رعاية</strong>.  
        لا تترددوا في التواصل معنا سواء كان لديك سؤال حول الكفالة، الدعم النفسي، التعليم، أو البرامج المجتمعية للأطفال الأيتام.
    </p>

    <div class="row mt-4 g-4 justify-content-center">
        {{-- معلومات الاتصال --}}
        <div class="col-md-8">
            <div class="p-4 bg-light rounded shadow-sm">
                <h3 style="color:#ff9800; margin-bottom:15px; font-size:1.6rem;">معلومات الاتصال</h3>
                <ul style="list-style:none; padding:0; line-height:2.2; font-size:16px; text-align:right; color:#000000;">
                    <li>📧 البريد الإلكتروني: <a href="mailto:info@raya.org">info@raya.org</a></li>
                    <li>📞 الهاتف: +970 599 123 456</li>
                    <li>📍 العنوان: شارع السلام، مدينة خانيونس، فلسطين</li>
                </ul>
                <p style="margin-top:15px; font-size:15px; color:#000000;">
                    يمكنكم الاتصال بنا في أي وقت خلال ساعات العمل الرسمية، وفريقنا مستعد للإجابة على جميع استفساراتكم وتقديم الدعم اللازم للأطفال الأيتام.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center" style="font-size:18px; color:#000000; line-height:1.8;">
        <p>
            نشكركم على تواصلكم معنا. كل رسالة تصلنا تُعالج بعناية لضمان تقديم أفضل دعم للأطفال الأيتام.  
            مشروع <strong>رعاية</strong> يسعى لبناء مجتمع متكافل، ونحن سعداء بمساهمتكم في هذه الرحلة الإنسانية.
        </p>
    </div>
</div>
@endsection

