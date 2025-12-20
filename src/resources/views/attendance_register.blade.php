@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance_register.css') }}" />
@endsection

@section('content')
<p class="main__situation">勤務外</p>
<p class="main__date">{{ now()->locale('ja')->isoFormat('YYYY年MM月DD日（ddd）') }}</p>
<p class="main__time">{{ now()->format('H:i') }}</p>
<form action="" class="main-form">
    <button class="main-form__button">出勤</button>
</form>
@endsection