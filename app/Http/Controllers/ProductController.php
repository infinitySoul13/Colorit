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
            'category'=> 'required',
            'price'=> 'required',
//            'rating' =>  'required',
//            'type' =>  'required',
        ]);
        $product = Product::create([
            'title'=>$request->get('title')??'',
            'description'=> $request->get('description')??'',
            'category'=> $request->get('category')??'',
            'price'=> $request->get('price')??'',
            'discount_price'=> $request->get('discount_price')??'',
            'discount'=> $request->get('discount')??'',
            'quantity'=>$request->get('quantity')??0,
//            'rating' =>$request->get('rating')??0,
            'type' => $request->get('type')??'',
            'size' => json_decode($request->get('size'))??[],
            'color' => json_decode($request->get('color'))??[],
            'src' => [],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $path = '/products';
        $new_files = [];

        if(!Storage::exists('/public'.$path)) {
            Storage::makeDirectory('/public'.$path);
        }
        $files = $request->file('files');
        if ($request->hasFile('files')) {
            foreach ($files as $key=>$file) {
                $name = Crypt::encryptString($file->getClientOriginalName());
                $ext = $file->getClientOriginalExtension();
                $file->storeAs("/public", $path.'/'.$name.'.'.$ext);
                $url = Storage::url('products/'.$name.'.'.$ext);
                $obj = (object)[];
                $obj->path = $url;
                $obj->name = $name.'.'.$ext;
                array_push($new_files, $obj);
            }
            $product->src = $new_files;
            $product->save();
            return response()->json([
                'success' => true,
                'message' => 'Product was created successfully',
                'product' => $product
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Product was not created successfully',
        ], 500);
//        return redirect()
//            ->route('products.index')
//            ->with('success', 'Товар успешно добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::withTrashed()->with(['reviews'])->find($id);
        return view('main.product', compact('product'));
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
                "message" => "Changes saved",
            ], 200);
    }

    public function destroy($id)
    {
        $product= Product::find($id);
        $product->delete();

        return response()
            ->json([
                "message" => "Product is deleted successfully",
            ], 200);
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->where('id', $id)->restore();

        return response()
            ->json([
                "product" => $product,
                "message" => "Product is restored",
            ], 200);
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->forceDelete();

        return response()
            ->json([
                "message" => "Product is destroyed successfully",
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
                "message" => "Product category successfully changed",
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
                "message" => "Products successfully deleted",
            ], 200);
    }

    public function restoreAll(Request $request)
    {
        foreach ($request->ids as $id) {
            $product = Product::onlyTrashed()->where('id', $id)->restore();
        }
        return response()
            ->json([
                "message" => "Products are restored",
            ], 200);
    }

    public function forceDeleteAll(Request $request)
    {
        foreach ($request->ids as $id) {
            $product = Product::withTrashed()->find($id);
            $images = $product->src;

            foreach ( $images as &$item) {
                $path = public_path() . '/images/' . $item['name'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $product->forceDelete();
        }

        return response()
            ->json([
                "message" => "Products are destroyed successfully",
            ], 200);
    }

    public function uploadFiles(Request $request) {
        $product = Product::find( $request->get("id"));
        $path = '/products';
        $new_files = $product->src;
        if( is_null($product->src))
        {
            $new_files = [];
        }

        if(!Storage::exists('/public'.$path)) {
            Storage::makeDirectory('/public'.$path);
        }
        $files = $request->file('files');
        if ($request->hasFile('files')) {
            foreach ($files as $key=>$file) {
                $name = Crypt::encryptString($file->getClientOriginalName());
                $ext = $file->getClientOriginalExtension();
                $file->storeAs("/public", $path.'/'.$name.'.'.$ext);
//                $file_path = $file->store('/public/products');
//                $file->storeAs("/public", $path.'/'.$name.'.'.$ext);
                $url = Storage::url('products/'.$name.'.'.$ext);
//                array_push($new_files,'/storage'.$path.'/'.$name.'.'.$ext);
//                array_push($new_files,$file_path);
                $obj = (object)[];
                $obj->path = $url;
                $obj->name = $name.'.'.$ext;
                $obj->filename = $file->getClientOriginalName();
                array_push($new_files, $obj);

//                array_push($new_files, '/storage/app/public'.$path.'/'.$name.'.'.$ext );
            }
            $product->src = $new_files;
            $product->save();
            return response()->json([
                'status' => 'success',
                'product' => $product
            ], 200);
        }
        return response()->json([
            'status' => 'error',
        ], 500);
    }

    public function uploadFile(Request $request) {
        $this->validate($request, [
            'file' => 'image'
        ]);
        $path = public_path().'/images/';
        $file = Input::file('file');
        $filename = $file->getClientOriginalName();
        $newImage = InterventionImage::make(Input::file('file'));
        $newImage->resize(350, 550);
        $newImage->save($path.$filename);

        if(file_exists($path.$filename)) {
            $input['filename'] = $filename;
            $input['mime'] = $file->getClientMimeType();
            $input['size'] = $file->getClientSize();

            return response()->json([
                'success' => true,
//                'id' => $image->id
            ], 200);
        }
        else {
            return response()->json([
                'success' => false
            ], 500);
        }
//Storage::disk('uploads')->put($filename,  File::get($file)
    }

    public function deleteFile(Request $request) {
        $name = $request->name;
        $path = storage_path('app/public').'/products/'.$name;
        if(file_exists($path)) {
            unlink($path);
            if ($request->id != '') {
                $product = Product::findOrFail($request->id);
                $product->update($request->only(["src"]));
            }
            return response()->json([
                'message' => "file was deleted"
            ], 200);
        }
        else {
            $product = Product::findOrFail($request->id);
            $product->update($request->only(["src"]));
        }
        return response()->json([
            'message' => "file does not exist",
            'product' => $product
        ], 200);
    }
}
