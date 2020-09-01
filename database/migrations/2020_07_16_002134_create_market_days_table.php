<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_days', function (Blueprint $table) {
            $table->id();
            $table->integer('market_id');
            $table->timestamps();
            $table->date('date');
            $table->char('employee')->nullable();
            $table->time('load_at', 6)->nullable();
            $table->time('setup_at', 6)->nullable();
            $table->text('packing_notes')->nullable();
            $table->text('market_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->float('estimated_revenue')->nullable();
            $table->float('actual_revenue')->nullable();
            $table->text('weather')->nullable();
            $table->text('wind')->nullable();
            $table->integer('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_days');
    }
}
