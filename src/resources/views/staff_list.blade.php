@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/staff_list.css') }}" />
@endsection

@section('content')
    <div class="main__inner">
        <div class="main-title">
            <div class="main-title__div">a</div>
            <h1 class="main-title__sentence"> スタッフ一覧</h1>
        </div>
        
        <table class="table">
            <tr>
                <th class="th">名前</th>
                <th class="th">メールアドレス</th>
                <th class="th">月次勤怠</th>
            </tr>
            @foreach($staffs as $staff)
                @php
                    $staff_id = $staff->id;
                @endphp
                <tr>
                    <td class="td">{{ $staff->name }}</td>
                    <td class="td">{{ $staff->email }}</td>
                    <td class="td">
                        <a class="td-detail" href="/admin/attendance/staff?id=$staff_id">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection