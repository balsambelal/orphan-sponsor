@extends('layouts.app')

@section('content')
<style>
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 40px;
    }

    .stats-card {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        padding: 25px;
        text-align: center;
        transition: transform 0.2s ease-in-out;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .stats-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .stats-number {
        font-size: 32px;
        font-weight: bold;
        color: #000;
    }

    /* ألوان مخصصة */
    .bg-blue { background-color: #007bff !important; color: #fff; }
    .bg-green { background-color: #28a745 !important; color: #fff; }
    .bg-gray { background-color: #6c757d !important; color: #fff; }
    .bg-cyan { background-color: #c15ea0ff !important; color: #fff; }

    /* تنسيق الرسم البياني */
    .chart-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 30px;
        margin-top: 50px;
    }
</style>

<div class="container text-center">
    <h2 class="fw-bold mt-4">📊 إحصائيات الموقع</h2>
    <p class="text-muted">نظرة شاملة على الأيتام والكفالات الحالية</p>

    <div class="stats-container">

        <div class="stats-card bg-blue">
            <div class="stats-icon">👦</div>
            <div class="stats-title">إجمالي الأيتام</div>
            <div class="stats-number">{{ $totalOrphans }}</div>
        </div>

        <div class="stats-card bg-green">
            <div class="stats-icon">🤝</div>
            <div class="stats-title">الأيتام المكفولين</div>
            <div class="stats-number">{{ $sponsoredOrphans }}</div>
        </div>

        <div class="stats-card bg-gray">
            <div class="stats-icon">🕊️</div>
            <div class="stats-title">الأيتام غير المكفولين</div>
            <div class="stats-number">{{ $unsponsoredOrphans }}</div>
        </div>

        <div class="stats-card bg-cyan">
            <div class="stats-icon">📅</div>
            <div class="stats-title">تم كفالتهم هذا الشهر</div>
            <div class="stats-number">{{ $sponsoredThisMonth }}</div>
        </div>

    </div>

    {{-- قسم الرسم البياني --}}
    <div class="chart-container mt-5">
        <h4 class="mb-4">📈 تمثيل بياني للإحصائيات</h4>
        <canvas id="orphansChart" height="120"></canvas>
    </div>
</div>

{{-- تضمين مكتبة Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('orphansChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'إجمالي الأيتام',
                'الأيتام المكفولين',
                'غير المكفولين',
                'كفالات هذا الشهر'
            ],
            datasets: [{
                label: 'عدد الأيتام',
                data: [
                    {{ $totalOrphans }},
                    {{ $sponsoredOrphans }},
                    {{ $unsponsoredOrphans }},
                    {{ $sponsoredThisMonth }}
                ],
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#6c757d',
                    '#c15ea0'
                ],
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#000',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endsection

