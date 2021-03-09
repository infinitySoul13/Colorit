@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Просмотр информации по продукту</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary" href="{{ route('products.index') }}"> Назад</a>
                            <a class="btn btn-link" href="{{ route('products.edit',$product->id) }}">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>


                    <table class="table mt-2">
                        <thead class="thead-light ">
                        <th>Параметр</th>
                        <th>Значение</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Заголовок <span
                                        class="badge badge-secondary">{{$product->is_active?"Активный":"Не активный"}}</span></td>
                            <td>
                                <p>{{$product->title}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Описание</td>
                            <td>
                                <p>{{$product->description}}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>Категория</td>
                            <td>
                                <p>{{$product->category}}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>Цена</td>
                            <td>
                                <p>{{$product->price}} руб.</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Бренд</td>
                            <td>
                                <p>{{$product->brand}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Кол-во</td>
                            <td>
                                <p>{{$product->quantity}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Номер производителя</td>
                            <td>
                                <p>{{$product->manufacturer_number}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Мера</td>
                            <td>
                                <p>{{$product->units}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Оригинальный номер</td>
                            <td>
                                <p>{{$product->original_number}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Минимум в упаковке</td>
                            <td>
                                <p>{{$product->min_in_pack}}</p>
                            </td>
                        </tr>

                        <tr>
                            <td>Изображение товара</td>
                            <td>
                                <img src="{{$product->img}}" alt="" class="img-thumbnail" style="object-fit: cover; height:150px;width: 150px;">
                            </td>
                        </tr>

                        <tr>
                            <td>Ссылка на товар на сайте</td>
                            <td>
                                <a href="{{$product->site_url}}" target="_blank">{{$product->site_url}}</a>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">
                                    Редактировать <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link" type="submit">Удалить <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        </tbody>
                    </table>

            </div>
        </div>
    </div>
@endsection
