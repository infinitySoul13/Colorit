@extends('layouts.app')


@section('content')
    <div class="container">
       <admin-product-edit :product="{{$product}}"></admin-product-edit>
    </div>
@endsection
