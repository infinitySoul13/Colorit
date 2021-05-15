@extends('layouts.main')

@section('title', 'COLORIT')

@section('content')
    <shop :products="{{json_encode($products)}}"></shop>
@endsection