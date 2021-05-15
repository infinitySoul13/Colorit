<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;


Route::get('/', function () {
    //$products = \App\Product::getLatestProducts();
    //$categories = \App\Product::getAllCategroies();

//    $defaultCat = rand(0,count($categories)-1);
    //$defaultCat = 1;
//    return view('main.main',compact('products','categories','defaultCat'));
    return view('main.main');
});
Route::get('/shop', function () {
    $products = \App\Product::all();
    return view('main.shop', compact('products'));
});
Route::get('/cart', function () {
    $products = \App\Product::all();
    return view('main.cart', compact('products'));
});

Route::get("/storage/products/{name}",function ($name){

    $file = Storage::disk('local')->get("public/products/$name");
    return (new Response($file, 200))
        ->header('Content-Type', 'image/jpeg');

});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');
Route::get('checkPhone/{phone}', 'UsersController@checkPhone');
Route::post('addName', 'UsersController@addName');
Route::post('/sendReview', 'ReviewController@store');

Route::prefix('products')->group(function () {
//    Route::get('/{id}', function ($id) {
//        $product = \App\Product::find($id);
//        return view('main.product', compact('product'));
//    });
    Route::get('/{id}', 'ProductController@show');
    Route::get('/more/{category}', 'ProductController@more');
    Route::get('/categories', 'ProductController@categories');
});
Route::middleware([IsAdmin::class])->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/get', 'CategoryController@get')->middleware('auth');
        Route::put('/{id}', 'CategoryController@update');
        Route::delete('/{id}', 'CategoryController@destroy');
        Route::post('/restore/{id}', 'CategoryController@restore');
        Route::post('/forceDelete/{id}', 'CategoryController@forceDelete');
        Route::resource('/', 'CategoryController');
    });
});
    Route::prefix('admin')->group(function () {
        Route::middleware([IsAdmin::class, 'auth'])->group(function () {
            Route::get("/", "ProductController@index")->name('admin.index');
            Route::get("/categories", "CategoryController@index")->name('admin.categories');
            Route::get('/users', "UsersController@index")->name('admin.users.index');
            Route::prefix('products')->group(function () {
                Route::view("/create", "admin.products.create")->name("admin.products.create");
                Route::get('/edit/{id}', "ProductController@edit")->name("admin.products.edit");
                Route::get('/get', 'ProductController@get')->middleware('auth');
                Route::post('/', 'ProductController@store');
                Route::put('/{id}', 'ProductController@update');
                Route::delete('/{id}', 'ProductController@destroy');
                Route::post('/restore/{id}', 'ProductController@restore');
                Route::post('/forceDelete/{id}', 'ProductController@forceDelete');
                Route::post('/updateAll', 'ProductController@updateAll');
                Route::post('/deleteAll', 'ProductController@destroyAll');
                Route::post('/restoreAll', 'ProductController@restoreAll');
                Route::post('/forceDeleteAll', 'ProductController@forceDeleteAll');
                Route::post('/upload', 'ProductController@uploadFile');
                Route::post('/upload-files', 'ProductController@uploadFiles');
                Route::post('/delete-file', 'ProductController@deleteFile');
//                Route::resource('/', 'ProductController');
            });
        });

//    Route::post('/search', 'HomeController@search')
//        ->name('users.search');
//    Route::get('/search_ajax/', 'HomeController@searchAjax')
//        ->name('users.ajax.search');

        Route::resources([
            'users' => 'UsersController',
//        'products' => 'ProductController',
        ]);
    });







