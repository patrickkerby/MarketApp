<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_quantities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // $table->integer('market_day_id')->unsigned();
            // $table->bigInteger('product_id')->unsigned();
            $table->decimal('packed', 5, 2)->nullable();
            $table->decimal('returned', 5, 2)->nullable();


            $table->foreignId('product_id')->constrained();
            $table->foreignId('market_day_id')->constrained('market_days')->onDelete('cascade');


            // $table->foreign('product_id')->references('id')->on('products');

            // $table->foreign('market_day_id')->references('id')->on('market_days');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_quantities');
    }
}
