@extends('user.pages.test_master')

@section('content')
    @include('user.pages.extra.partials.lesson_player', ['isFreeMode' => false])
@endsection
