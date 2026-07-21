<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketDayWizardDraftsTable extends Migration
{
    public function up()
    {
        Schema::create('market_day_wizard_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->json('markets');
            $table->json('products');
            $table->json('product_quantities')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('market_day_wizard_drafts');
    }
}
