<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->json('src');
            $table->decimal('price', 8, 2)->default(0.0);
            $table->decimal('discount', 8, 2)->default(0.0);
            $table->decimal('discount_price', 8, 2)->default(0.0);
            $table->json('color')->nullable();
            $table->json('size')->nullable();
//            $table->integer('rating')->default(0);
            $table->integer('quantity')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
