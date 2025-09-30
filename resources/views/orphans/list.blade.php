@extends('layouts.app')
@section('content')
<div class="container">
    <h3 class="mb-4">قائمة الأيتام</h3>

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>الاسم</th>
            <th>العمر</th>
            <th>حالة الكفالة</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orphans as $orphan)
        <tr>
            <td>{{ $orphan->name }}</td>
            <td>{{ $orphan->age }} سنة</td>
            <td>{{ $orphan->is_sponsored ? 'مكفول' : 'غير مكفول' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


    <a href="{{ route('orphans.dashboard') }}" class="btn btn-primary">رجوع إلى لوحة التحكم</a>
</div>
@endsection
