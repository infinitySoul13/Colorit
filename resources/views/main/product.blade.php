@extends('layouts.main')

@section('title', 'COLORIT')

@section('content')
    <product-page :product="{{json_encode($product)}}"></product-page>
@endsection
