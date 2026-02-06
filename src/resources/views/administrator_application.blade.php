@extends('layouts.admin')

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
            <a href="/stamp_correction_request/list?tab=waiting" class="tab-link {{ $tab === 'waiting' ? 'active' : '' }}">承認待ち</a>
            <a href="/stamp_correction_request/list?tab=done" class="tab-link {{ $tab === 'done' ? 'active' : '' }}">承認済み</a>
        </div>
        
        @if($tab === 'waiting')
            <table class="table">
                <tr>
                    <th class="th">状態</th>
                    <th class="th">名前</th>
                    <th class="th">対象日時</th>
                    <th class="th">申請理由</th>
                    <th class="th">申請日時</th>    
                    <th class="th">詳細</th>
                </tr>
                @foreach ($unapproved_works as $unapproved_work)
                    @php
                        $unapproved_work_id = $unapproved_work->work_id;
                    @endphp
                    <tr>
                        <td class="td">承認待ち</td>
                        <td class="td">{{ $unapproved_work->user->name }}</td>
                        <td class="td">{{ Carbon\Carbon::parse($unapproved_work->date)->format('Y/m/d'); }}</td>
                        <td class="td">{{ $unapproved_work->remarks }}</td>
                        <td class="td">{{ Carbon\Carbon::parse($unapproved_work->application_date)->format('Y/m/d'); }}</td>
                        <td class="td"><a href="/stamp_correction_request/approve/{{$unapproved_work_id}}" class="td-detail">詳細</a></td>
                    </tr>
                @endforeach
            </table>

        @elseif($tab === 'done')
            <table class="table">
                <tr>
                    <th class="th">状態</th>
                    <th class="th">名前</th>
                    <th class="th">対象日時</th>
                    <th class="th">申請理由</th>
                    <th class="th">申請日時</th>    
                    <th class="th">詳細</th>
                </tr>
                @foreach ($works as $work)
                    @php
                        $work_id = $work->id;
                    @endphp
                    <tr>
                        <td class="td">承認済み</td>
                        <td class="td">{{ $work->user->name }}</td>
                        <td class="td">{{ Carbon\Carbon::parse($work->date)->format('Y/m/d'); }}</td>
                        <td class="td">{{ $work->remarks }}</td>
                        <td class="td">{{ Carbon\Carbon::parse($work->application_date)->format('Y/m/d'); }}</td>
                        <td class="td"><a href="/stamp_correction_request/approve/{{$work_id}}" class="td-detail">詳細</a></td>
                    </tr>
                @endforeach
            </table>
        @endif

    </div>
@endsection