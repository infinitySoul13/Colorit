<?php

namespace App\Http\Controllers;

use App\Category;
use App\Flag;
use App\Product;
use ATehnix\VkClient\Auth;
use ATehnix\VkClient\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Process\Process;

class ProductController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $products = Product::withTrashed()->orderBy('id', 'ASC')
//            ->paginate(15);
//        return view('admin.products.index', compact('products'))
//            ->with('i', ($request->get('page', 1) - 1) * 15);
        return view('admin.index');
    }

    public function get(Request $request)
    {
        $products = Product::all();
        $deleted_products = Product::onlyTrashed()->get();
        return response()
            ->json([
                'products' => $products,
                'deleted_products' => $deleted_products,
            ], 200);
    }
    public function more(Request $request, $category)
    {
        $products = Product::orderBy('id', 'ASC')->where('is_active', true)->where('category', $category)
            ->paginate(30);
        return response()
            ->json([
                'products' => $products
            ], 200);
    }
    public function categories(Request $request)
    {
        $categories = Product::all()->unique('category');
        $tmp = [];
        $products=[];
        foreach ($categories as $category) {
            $prods = Product::orderBy('id', 'ASC')->where('is_active', true)->where('category', $category->category)
                ->paginate(30);
            $p = json_decode(json_encode($prods));
            $prods_count = count($p->data);
            if($prods_count>0)
            {
                $cat = (object)[];
                $cat->page = 2;
                $cat->title=$category->category;
                $cat->last_page=$p->last_page;
                array_push($tmp, $cat);
                foreach ($p->data as $product)
                {
                    array_push($products, $product);
                }
            }
        }
        return response()
            ->json([
                'categories' => $tmp,
                'products' => $products,
            ], 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.products.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=> 'required',
            'description'=> 'required',
            'category'=> 'required',
            'price'=> 'required',
            'img'=> 'required',
            'site_url'=> 'required',
            'is_active'=> 'required',
        ]);
        $product = Product::create([
            'title'=>$request->get('title')??'',
            'description'=> $request->get('description')??'',
            'category'=> $request->get('category')??'',
            'price'=> $request->get('price')??'',
            'img'=> $request->get('image_url')??'',
            'site_url'=> $request->get('site_url')??'',
            'is_active'=> $request->get('is_active')??true,
            'from_vk'=> $request->get('from_vk')??false,

            'brand'=>$request->get('brand')??'',
            'quantity'=>$request->get('quantity')??0,
            'manufacturer_number'=>$request->get('manufacturer_number')??'',
            'units'=>$request->get('units')??'',
            'original_number'=>$request->get('original_number')??'',
            'min_in_pack'=>$request->get('min_in_pack')??'',

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return redirect()
            ->route('products.index')
            ->with('success', 'Товар успешно добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::withTrashed()->find($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::withTrashed()->find($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $param = $request->get("param");
        $value = $request->get("value");

        $product = Product::withTrashed()->find($id);
        $product[$param] = $value;
        $product->save();

        return response()
            ->json([
                'product' => $product,
                "message" => "Изменения сохранены",
            ], 200);
//        $request->validate([
//            'title'=> 'required',
//            'description'=> 'required',
//            'category'=> 'required',
//            'price'=> 'required',
//            'img'=> 'required',
//            'is_active'=> 'required',
//        ]);
//
//        $product = Product::find($id);
//        $product->title = $request->get("title");
//        $product->description = $request->get("description");
//        $product->category = $request->get("category");
//        $product->price = $request->get('price');
//        $product->image_url = $request->get('img');
//        $product->site_url = $request->get('site_url')??'';
//        $product->is_active = $request->get('is_active')??true;
//        $product->from_vk = $request->get('from_vk')??false;
//
//        $product->brand = $request->get('brand')??'';
//        $product->quantity = $request->get('quantity')??0;
//        $product->manufacturer_number = $request->get('manufacturer_number')??'';
//        $product->units = $request->get('units')??'';
//        $product->original_number = $request->get('original_number')??'';
//        $product->min_in_pack = $request->get('min_in_pack')??'';
//
//        $product->save();
//
//        return redirect()
//            ->route('products.index')
//            ->with('success', 'Товар успешно отредактирован');
    }

    public function destroy($id)
    {
        $product= Product::find($id);
        $product->delete();

        return response()
            ->json([
                "message" => "Товар успешно удалён",
            ], 200);
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->where('id', $id)->restore();

        return response()
            ->json([
                "product" => $product,
                "message" => "Товар восстановлен",
            ], 200);
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->forceDelete();

        return response()
            ->json([
                "message" => "Товар успешно полностью удалён",
            ], 200);
    }

    public function updateAll(Request $request)
    {
        foreach ($request->ids as $id) {
            $product = Product::withTrashed()->find($id);
            $param = $request->get("key");
            $value = $request->get("value");
            $product[$param] = $value;
            $product->save();
//            $product->category = $request->category;
//            $product->save();
        }
        return response()
            ->json([
                "message" => "Категория товаров успешно изменена",
            ], 200);
    }

    public function destroyAll(Request $request)
    {
        foreach ($request->ids as $id) {
            $product= Product::find($id);
            $product->delete();
        }

        return response()
            ->json([
                "message" => "Товары успешно удалены",
            ], 200);
    }

    public function restoreAll(Request $request)
    {
        foreach ($request->ids as $id) {
            $product = Product::onlyTrashed()->where('id', $id)->restore();
        }
        return response()
            ->json([
                "message" => "Товары восстановлены",
            ], 200);
    }

    public function forceDeleteAll(Request $request)
    {
        foreach ($request->ids as $id) {
            $product = Product::withTrashed()->find($id);
            $product->forceDelete();
        }

        return response()
            ->json([
                "message" => "Товары успешно полностью удалены",
            ], 200);
    }
    public function uploadVk(Request $request)
    {
        $products = Product::where('from_vk', true)->forceDelete();
        $auth = new Auth('7727122', 'oFaahFNl4RlW1mBbEAiU', 'https://xn--80aeg0bdbz.com/products/uploadVk', 'market');

        $token = null;

        if ($request->has("code")) {
            $token = $auth->getToken($request->get('code'));

            $api = new Client;
            $api->setDefaultToken($token);

            $response = $api->request('market.getAlbums', [
                'owner_id' => -191403898,
                'count' => 50
            ]);


//            Product::truncate();
            $products = Product::where('from_vk', true)->forceDelete();

            //работает
            foreach ($response["response"]["items"] as $item) {
                //echo $item["id"].$item["title"]." ".$item["photo"]["photo_807"]."<br>";

                $category = Category::where('title', $item["title"])->first();
                $img = $item["photo"]["photo_604"];
                if (!is_null($category)) {
                    $category->img = $img;
                    $category->save();
                }
                else{
                    $new_category = Category::create([
                        'title' => $item["title"],
                        'words' => [],
                        'img' => $img
                    ]);
                }

                $response2 = $api->request('market.get', [
                    'owner_id' => -191403898,
                    'album_id' => $item["id"],
                    'count' => 200
                ]);

                foreach ($response2["response"]["items"] as $item2) {
                    //echo $item2["description"]." ".$item2["price"]["text"]." ".$item2["thumb_photo"]." ".$item2["title"]."<br>";


                    preg_match_all('/\bПроизводитель: \b(\w+)/u', $item2["description"], $matches);

                    $brand = $matches[1][0] ?? '';

//                    preg_match_all('|\d+|', $item2["price"]["text"], $matches);
//
//                    $price = $matches[0][0] ?? 0;
                    $price = intval($item2["price"]["amount"]) / 100;
                    Product::create([
                        'title' => $item2["title"],
                        'description' => $item2["description"],
                        'category' => $item["title"],

                        'price' => $price,
                        'img' => $item2["thumb_photo"],
                        'site_url' => '',
                        'is_active' => true,
                        'from_vk' => true,
                        'brand'=>$brand,
                        'quantity'=> 0,
                        'manufacturer_number'=>'',
                        'units'=> '',
                        'original_number'=> '',
                        'min_in_pack'=> '',
                    ]);
                }


                sleep(2);

            }
            //dd($response["items"]);

        }
        return redirect()->action([ProductController::class, 'index']);
//        return view('admin.index', compact("auth", "token"));
//        return response()
//            ->json([
////                'products' => $products,
//                'resp' => $request->has("code"),
////                'response2' => $response2,
//                "message" => "Товары из ВК успешно загружены",
//            ], 200);
    }

    public function uploadFile(Request $request){
        ini_set('max_execution_time', 1000000);
        ini_set('memory_limit', '100000M');
        ini_set('upload_max_filesize', '512M');
        ini_set('post_max_size', '512M');
        $products = Product::where('from_vk', false)->forceDelete();
        $categories = Category::all();
        if(!count($categories)) {
            Category::create([
                'title' =>'Масло трансмиссионное',
                'words' => ["трансмиссионное", "трансмис", "трансмиссион", "трансм","трансмиccионное"],
                'img' => '/assets/images/cat_6.jpg'
            ]);
            Category::create([
                'title' =>'Щетки',
                'words' => ['Щетки','Щётки','Щетка','Щётка'],
                'img' => '/assets/images/cat.png'
            ]);
            Category::create([
                'title' =>'Масло моторное',
                'words' => ["моторное","мот", "мотор"],
                'img' => '/assets/images/cat_8.jpg'
            ]);
            Category::create([
                'title' =>'АКБ',
                'words' => ['АКБ'],
                'img' => '/assets/images/cat_9.jpg'
            ]);
            Category::create([
                'title' =>'Лампы',
                'words' => ['Лампа'],
                'img' => '/assets/images/cat_10.jpg'
            ]);
            Category::create([
                'title' =>'Жидкость',
                'words' => ['Жидкость'],
                'img' => '/assets/images/cat_3.jpg'
            ]);
            Category::create([
                'title' =>'Выхлопы',
                'words' => ['Выхлоп'],
                'img' => '/assets/images/cat_4.jpg'
            ]);
            Category::create([
                'title' =>'Кузовы',
                'words' => ['Кузов'],
                'img' => '/assets/images/cat_7.jpg'
            ]);
            Category::create([
                'title' =>'Дворники',
                'words' => ['Дворники'],
                'img' => '/assets/images/cat.png'
            ]);
            Category::create([
                'title' =>'Другое',
                'words' => [],
                'img' => '/assets/images/cat.png'
            ]);
            $categories = Category::all();
        }

        $file = Input::file('file');
        $extension = $file->getClientOriginalExtension();
        $filePath = $file->getPathName();
//        $path='/files';
//        if(!Storage::exists($path)) {
//            Storage::makeDirectory($path);
//        }
//        $name = Crypt::encryptString($file->getClientOriginalName());
//        $file->storeAs("/public", $path.'/'.$name.'.'.$extension);
//        $file_path = '/storage'.$path.'/'.$name.'.'.$extension;
//        $url = Storage::url($name.'.'.$extension);

        $flag_table = Flag::create([
            'file_name'=>$filePath,
            'extension' => $extension,
            'imported' => false
        ]);
//        $flag_table->imported = false; //file was not imported
//        $flag_table->save();

//        $process = new Process('php ../artisan products:load '+ $filePath+' '+$extension);
//        $process = new Process('php ../artisan products:load');
//        $process->run();
//        $exitCode = Artisan::call('products:load');
//        $exitCode = Artisan::queue('products:load', [ '--queue' => 'default'
//        ]);

//        $file = Flag::where('imported','=','0')
//            ->orderBy('created_at', 'DESC')
//            ->first();

        if( $flag_table['extension']  == 'csv') {
            $reader = IOFactory::createReader('Csv');
        }
        else{
            $reader = IOFactory::createReader('Xlsx');
        }

        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($flag_table['file_name']);

        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            if ($row->getRowIndex() <= 2)
                continue;

            $data = array();
            $category = '';
            $img = '/assets/images/cat.png';
            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case "B":
                        try {
                            $data["brand"] = $cell->getValue();
                        } catch (PDOException $e) {

                        }

                        break;
                    case "C":
                        $data["manufacturer_number"] = $cell->getValue();
                        break;
                    case "D":
                        $data["title"] = $cell->getValue();
                        $delimiters = array(" ","  ",",",".","|",":");
                        $ready = str_replace($delimiters, $delimiters[0], $cell->getValue());
                        $launch = explode($delimiters[0], $ready);
                        $category = 'Другое';
                        if(count($categories)){
                            foreach ($categories as $cat) {
                                $words = $cat->words;
                                foreach ($words as $word) {
                                    if (in_array($word, $launch))
                                    {
                                        $category = $cat->title;
                                        $img= $cat->img;
                                        break;
                                    }
                                }
                            }
                        }
                        else {
                            if (in_array("Масло", $launch) || in_array("масло", $launch) || in_array("масл", $launch)) {
                                if (in_array("трансмиссионное", $launch) || in_array("трансмис", $launch) || in_array("трансмиссион", $launch)|| in_array("трансм", $launch)|| in_array("трансмиccионное", $launch) ) {
                                    $category = 'Масло трансмиссионное';
                                    $img='/assets/images/cat_6.jpg';
                                }
                                if (in_array("моторное", $launch) || in_array("мот", $launch) || in_array("мотор", $launch) ) {
                                    $category = 'Масло моторное';
                                    $img='/assets/images/cat_8.jpg';
                                }
                            }
                            if (in_array("АКБ", $launch))
                            {
                                $category = 'АКБ';
                                $img='/assets/images/cat_9.jpg';
                            }
                            if (in_array("Щетки", $launch) || in_array("Щётки", $launch) || in_array("Щетка", $launch) || in_array("Щётка", $launch))
                            {
                                $category = 'Щетки';
                            }
                            if (in_array("Лампа", $launch))
                            {
                                $category = 'Лампы';
                                $img='/assets/images/cat_10.jpg';
                            }
                            if (in_array("Жидкость", $launch))
                            {
                                $category = 'Жидкость';
                                $img='/assets/images/cat_3.jpg';
                            }
                            if (in_array("Дворники", $launch))
                            {
                                $category = 'Дворники';
                            }
                            if (in_array("Выхлоп", $launch))
                            {
                                $category = 'Выхлопы';
                                $img='/assets/images/cat_4.jpg';
                            }
                            if (in_array("Кузов", $launch))
                            {
                                $category = 'Кузовы';
                                $img='/assets/images/cat_7.jpg';
                            }
                        }

                        break;
                    case "E":
                        $data["units"] = $cell->getValue();
                        break;
                    case "F":
                        $data["min_in_pack"] = $cell->getValue();
                        break;
                    case "G":
                        $data["original_number"] = $cell->getValue();
                        if($cell->getValue()==null) {
                            $data["original_number"] = '';
                        }
                        break;
                    case "H":
                        $data["quantity"] = $cell->getValue();
                        break;
                    case "J":
                        if($cell->getValue()!=null) {
                            $data["price"] = $cell->getValue();
                        }
                        else if ($cell->getValue()=='' || $cell->getValue()== '-') {
                            $data["price"] = 0;
                        }
                        else{
                            $data["price"] = 0;
                        }
                        break;
                }
            }

            try {
                Product::create([
                    'title' => $data["title"],
                    'brand' => $data["brand"],
                    'price' => $data["price"],
                    'quantity' => $data["quantity"],
                    'manufacturer_number' => $data["manufacturer_number"],
                    'units' => $data["units"],
                    'original_number' => $data["original_number"],
                    'min_in_pack' => $data["min_in_pack"],
                    'img' => $img,
                    'description' => '',
                    'category' => $category,
                    'from_vk'=> false,
                    'is_active' => true
                ]);
                $flag_table = $flag_table->fresh(); //reload from the database
                $flag_table['rows_imported'] = $flag_table['rows_imported'] + 1;
                $flag_table->save();
            } catch (Exception $e) {
                continue;
            }

        }
        $flag_table['imported'] = true;
        $flag_table->save();
            ini_set('max_execution_time', 60);
        return response()
            ->json([
//                "message" => "Товары загружаются, подождите пожалуйста",
                "message" => "Товары успешно загружены",
            ], 200);
    }

    public function status(Request $request)
    {
        $flag_table = Flag::orderBy('created_at', 'desc')
            ->first();
        if(empty($flag_table)) {
            return response()->json(['message' => 'Файл не найден', 'status'=>1]); //nothing to do
        }
        if($flag_table->imported == 1) {
            return response()->json(['message' => 'Товары успешно загружены', 'status'=>1]);
        } else {
            return response()->json(['message' => 'Товары ещё загружаются. Пока загружено '.$flag_table->rows_imported.' товаров', 'status'=>0]);
        }
    }
}
