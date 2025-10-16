@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>الكفالات الخاصة بـ: {{ $orphan->name }}</h3>

    @if($orphan->sponsorships->isEmpty())
        <p class="text-muted">لا توجد كفالات بعد.</p>
    @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>الكفيل</th>
                    <th>المبلغ</th>
                    <th>تاريخ البداية</th>
                    <th>تاريخ النهاية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orphan->sponsorships as $sponsorship)
                    <tr>
                        <td>{{ $sponsorship->sponsor->name ?? '-' }}</td>
                        <td>{{ $sponsorship->amount ?? '-' }}</td>
                        <td>{{ $sponsorship->start_date ?? '-' }}</td>
                        <td>{{ $sponsorship->end_date ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">رجوع</a>
</div>
@endsection
