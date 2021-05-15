<?php

namespace App\Http\Controllers;

use App\Product;
use App\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                    'name'=> 'required',
                    'email'=> 'required',
//                    'phone'=> 'required',
                    'message'=> 'required',
                ]);
                $review = Review::create([
                    'name'=>$request->get('name')??'',
                    'email'=> $request->get('email')??'',
                    'phone'=> $request->get('phone')??'',
                    'message'=> $request->get('message')??'',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $product = Product::find($request->id);
                $product->reviews()->attach($review->id);
                return response()->json([
                    'success' => true,
                    'message' => 'Review was created successfully',
                    'review' => $review
                ], 200);
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Product was not created successfully',
//                ], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}
