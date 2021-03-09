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

//use \ATehnix\VkClient\Requests\Request as VkRequest;
//use \ATehnix\VkClient\Auth as VkAuth;
use App\Http\Middleware\IsAdmin;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;


Route::get('/', function () {
    //$products = \App\Product::getLatestProducts();
    //$categories = \App\Product::getAllCategroies();

//    $defaultCat = rand(0,count($categories)-1);
    //$defaultCat = 1;
//    return view('main.main',compact('products','categories','defaultCat'));
    return view('main.main');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::post('vin/create', 'VinController@create');
Route::get('vin/delete/{id}', 'VinController@destroy');

Route::get('vin/categories', 'VinController@getCategories');
Route::get('vin/body/{categroyId}', 'VinController@getBodyStyles');
Route::get('vin/marks/{categroyId}', 'VinController@getMarks');
Route::get('vin/models/{categroyId}/{marksId}', 'VinController@getModels');
Route::get('vin/gear/{categroyId}', 'VinController@getGearboxes');
Route::get('vin/driver/{categroyId}', 'VinController@getDriverType');
Route::get('vin/fuel', 'VinController@getFuelType');
Route::get('vin/options/{categroyId}', 'VinController@getAutoOptions');
Route::get('vin/history/{user_id}', 'VinController@getVinRequests');
Route::post('sendVinRequest', 'VinController@sendVinRequest');
Route::post('vin/tyres/add', 'VinController@addTyres');
Route::post('vin/disks/add', 'VinController@addDisks');
Route::post('sendTyresRequest', 'VinController@sendTyresRequest');
Route::post('sendDisksRequest', 'VinController@sendDisksRequest');

Route::post('vendorSearch', 'VinController@vendorSearch');

Route::get('checkPhone/{phone}', 'UsersController@checkPhone');
Route::post('addName', 'UsersController@addName');

Route::prefix('products')->group(function () {
    Route::get('/more/{category}', 'ProductController@more');
    Route::get('/categories', 'ProductController@categories');
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/get', 'ProductController@get')->middleware('auth');
        Route::put('/{id}', 'ProductController@update');
        Route::delete('/{id}', 'ProductController@destroy');
        Route::post('/restore/{id}', 'ProductController@restore');
        Route::post('/forceDelete/{id}', 'ProductController@forceDelete');
        Route::post('/updateAll', 'ProductController@updateAll');
        Route::post('/deleteAll', 'ProductController@destroyAll');
        Route::post('/restoreAll', 'ProductController@restoreAll');
        Route::post('/forceDeleteAll', 'ProductController@forceDeleteAll');
        Route::post('/upload', 'ProductController@uploadFile');
        Route::post('/status', 'ProductController@status');
        Route::get('/uploadVk', 'ProductController@uploadVk');
    });
    Route::resource('/', 'ProductController');
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
        Route::get("/", "ProductController@index")->name('admin.index')->middleware('auth');
        Route::middleware([IsAdmin::class])->group(function () {
            Route::get("/categories", "CategoryController@index")->name('admin.categories')->middleware('auth');
            Route::get('/users', "UsersController@index")->name('admin.users.index')->middleware('auth');
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







