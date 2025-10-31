@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #ffd782;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 40px;
    }

    .stats-card {
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        color: #000; /* نص أسود */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .stats-icon {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .stats-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #000; /* نص أسود */
    }

    .stats-number {
        font-size: 32px;
        font-weight: bold;
        color: #000; /* نص أسود */
    }

    /* ألوان الديفات من الأغمق للأفتح لتناسب الخلفية والنافبار */
    .bg-blue {
        background: linear-gradient(135deg, #FF9E1F, #FFAB5E);
    }
    .bg-green {
        background: linear-gradient(135deg, #FFB347, #FFC75F);
    }
    .bg-gray {
        background: linear-gradient(135deg, #FFD382, #FFE0A3);
    }
    .bg-cyan {
        background: linear-gradient(135deg, #FFB84D, #FFD9B3);
    }

    .chart-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .chart-box {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 30px;
        text-align: center;
    }

    h2.fw-bold {
        color: #333;
        margin-top: 20px;
    }

    p.text-muted {
        color: #555 !important;
    }
</style>

<div class="container text-center">
    <h2 class="fw-bold">📊 إحصائيات الموقع</h2>
    <p class="text-muted">نظرة شاملة على الأيتام والكفالات والكفلاء</p>

    <div class="stats-container">
        <div class="stats-card bg-blue">
            <div class="stats-icon">👦</div>
            <div class="stats-title">إجمالي الأيتام</div>
            <div class="stats-number">{{ $totalOrphans }}</div>
        </div>

        <div class="stats-card bg-green">
            <div class="stats-icon">🤝</div>
            <div class="stats-title">إجمالي الكفلاء</div>
            <div class="stats-number">{{ $totalSponsors }}</div>
        </div>

        <div class="stats-card bg-gray">
            <div class="stats-icon">📄</div>
            <div class="stats-title">إجمالي الكفالات</div>
            <div class="stats-number">{{ $totalSponsorships }}</div>
        </div>

    </div>

    <div class="chart-container mt-5">
        <div class="chart-box">
            <h5 class="mb-4">إحصائيات الأيتام</h5>
            <canvas id="orphansChart" height="200"></canvas>
        </div>

        <div class="chart-box">
            <h5 class="mb-4">إحصائيات الكفلاء</h5>
            <canvas id="sponsorsChart" height="200"></canvas>
        </div>

        <div class="chart-box">
            <h5 class="mb-4">إحصائيات الكفالات</h5>
            <canvas id="sponsorshipsChart" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('orphansChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['مكفولين', 'غير مكفولين'],
            datasets: [{
                data: [{{ $sponsoredOrphans }}, {{ $unsponsoredOrphans }}],
                backgroundColor: ['#FF9E1F', '#FFD382']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('sponsorsChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['الكفلاء', 'عدد الكفالات المرتبطة بالكفلاء'],
            datasets: [{
                data: [{{ $totalSponsors }}, {{ $totalSponsorships }}],
                backgroundColor: ['#FFAB5E', '#FFC75F']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('sponsorshipsChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['إجمالي الكفالات', 'كفالات هذا الشهر'],
            datasets: [{
                data: [{{ $totalSponsorships }}, {{ $sponsoredThisMonth }}],
                backgroundColor: ['#FFC75F', '#FFD9B3']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endsection

