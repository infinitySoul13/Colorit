<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
        return view('admin.categories');
    }

    public function get(Request $request)
    {
        $categories = Category::withTrashed()->get();
        $products = Product::withTrashed()->get();
        $products = $products->unique('category');
        $products_category = [];
//         foreach ($products as $product)
//         {
//             array_push($products_category, $product->title);
//         }
//         foreach ($categories as $category)
//         {
//             if(!in_array($category->title, $products_category))
//             {
//                 $category = Category::create([
//                     'title'=>$category->title,
//                     'words'=>[],
//                 ]);
//             }
//         }

        foreach ($categories as $category)
        {
            array_push($products_category, $category->title);
        }

        foreach ($products as $product)
        {
            if(!in_array($product->category, $products_category))
            {
                 $category = Category::create([
                     'title'=>$product->category,
                     'words'=>[],
                 ]);
            }
        }

        $categories = Category::all();
        $deleted_categories = Category::onlyTrashed()->get();
        return response()
            ->json([
                'categories' => $categories,
                'products' => $products,
                'deleted_categories' => $deleted_categories,
            ], 200);
    }
    public function store(Request $request)
    {
        $title=$request->get('title');
        $words=$request->get('words');
        $img=$request->get('img');
        if($title == '') {
            return response()
                ->json([
                    'status' => 'error',
                    "message" => "Название категории не может быть пустым",
                ], 200);
        }
        $category= Category::where('title',$title)->first();
        if(!is_null($category)) {
            return response()
                ->json([
                    'status' => 'error',
                    "message" => "Категория с таким названием уже существует",
                ], 200);
        }
        if($img =='') {
            $img = '/assets/images/cat.png';
        }
        $category = Category::create([
            'title'=>$title,
            'words'=>$words,
            'img'=>$img,
        ]);
        return response()
            ->json([
                'img' => $img,
                'status' => 'success',
                'category' => $category,
                "message" => "Категория успешно добавлена",
            ], 200);
    }

    public function update(Request $request, $id)
    {
        $param = $request->get("param");
        $value = $request->get("value");

        $category = Category::withTrashed()->find($id);
        if($value !== "Другое") {

            if($param == 'title')
            {
                $products = Product::withTrashed()->where('category', $category->title)->update(['category' => $value]);
            }
            if($param == 'img')
            {
                if($value != '')
                {
                    $products = Product::withTrashed()->where('category', $category->title)->where('img', $category->img)->update(['img' => $value]);
                }
                else{
                    $products = Product::withTrashed()->where('category', $category->title)->where('img', $category->img)->update(['img' => '/assets/images/cat.png']);
                }

            }
            $category[$param] = $value;
            $category->save();
        }
        return response()
            ->json([
                'category' => $category,
                "message" => "Изменения сохранены",
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category= Category::find($id);
        $products = Product::withTrashed()->where('category', $category['title'])->update(['category' => 'Другое']);
        $category->delete();

        return response()
            ->json([
                "message" => "Категория успешно удалена",
            ], 200);
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->where('id', $id)->restore();

        return response()
            ->json([
                "category" => $category,
                "message" => "Категория восстановлена",
            ], 200);
    }

    public function forceDelete($id)
    {
        $category = Category::withTrashed()->find($id);
        $category->forceDelete();

        return response()
            ->json([
                "message" => "Категория успешно полностью удалена",
            ], 200);
    }
}
