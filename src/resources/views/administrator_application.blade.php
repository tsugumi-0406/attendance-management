@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/administrator_application.css') }}" />
@endsection

@section('content')
    <div class="main__inner">
        <div class="main-title">
            <div class="main-title__div">a</div>
            <h1 class="main-title__sentence"> 申請一覧</h1>
        </div>
        <div class="page-link">
            <a href="" class="tab-link">承認待ち</a>
            <a href="" class="tab-link">承認済み</a>
        </div>
        
        <table class="table">
            <tr>
                <th class="th">状態</th>
                <th class="th">名前</th>
                <th class="th">対象日時</th>
                <th class="th">申請理由</th>
                <th class="th">申請日時</th>    
                <th class="th">詳細</th>
            </tr>
            <tr>
                <td class="td">承認待ち</td>
                <td class="td">西令奈</td>
                <td class="td">2023/06/03</td>
                <td class="td">遅延のため</td>
                <td class="td">2023/06/04</td>
                <td class="td"><span class="td-detail">詳細</span></td>
            </tr>
        </table>
    </div>
@endsection