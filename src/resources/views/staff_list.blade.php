@extends('layouts.app')

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
            <tr>
                <td class="td">山田太郎</td>
                <td class="td">aaa@gmail.com</td>
                <td class="td"><span class="td-detail">詳細</span></td>
            </tr>
        </table>
    </div>
@endsection