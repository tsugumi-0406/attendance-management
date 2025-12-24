@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/administrator_attendance_list.css') }}" />
@endsection

@section('content')
    <div class="main__inner">
        <div class="main-title">
            <div class="marin-title__div">a</div>
            <h1 class="main-title__sentence"> 〇年〇月〇日の勤怠</h1>
        </div>
        <div class="month">
            <div class="month-before">前月</div>
            <div class="month-now">該当月</div>
            <div class="month-after">翌月</div>
        </div>
        <table class="table">
            <tr>
                <th class="th">名前</th>
                <th class="th">出勤</th>
                <th class="th">退勤</th>
                <th class="th">休憩</th>
                <th class="th">合計</th>
                <th class="th">詳細</th>
            </tr>
            <tr>
                <td class="td">山田 太郎</td>
                <td class="td">出勤</td>
                <td class="td">退勤</td>
                <td class="td">休憩</td>
                <td class="td">合計</td>
                <td class="td"><span class="td-detail">詳細</span></td>
            </tr>
        </table>
    </div>
@endsection