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
            $table->increments('id');
            $table->string('title')->default('');
            $table->string('brand')->default('');
            $table->string('manufacturer_number')->default('')->nullable();
            $table->string('category')->default('')->nullable();
            $table->string('units')->default('')->nullable();
            $table->string('min_in_pack')->default('')->nullable();
            $table->string('original_number')->default('')->nullable();

            $table->decimal('price', 8, 2)->default(0.0);
            // $table->decimal('old_price', 8, 2)->nullable();
            $table->integer('quantity')->default(0)->nullable();
//            $table->boolean('is_top')->default(false);
//            $table->boolean('is_new')->default(false);
            $table->boolean('is_active')->default(true)->nullable();
            $table->string('img',1000)->default('')->nullable();

            $table->longText('description')->nullable();
            $table->string('site_url',1000)->default('')->nullable();
            $table->boolean('from_vk')->default(false)->nullable();
            // $table->integer('category_id')->unsigned();
            // $table->foreign('category_id')->references('id')->on('categories');
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
