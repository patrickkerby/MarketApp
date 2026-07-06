<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesForAnalyticsQueries extends Migration
{
    public function up()
    {
        Schema::table('market_days', function (Blueprint $table) {
            $table->index(['state', 'date'], 'market_days_state_date_index');
            $table->index(['state', 'market_id', 'date'], 'market_days_state_market_date_index');
        });
    }

    public function down()
    {
        Schema::table('market_days', function (Blueprint $table) {
            $table->dropIndex('market_days_state_date_index');
            $table->dropIndex('market_days_state_market_date_index');
        });
    }
}
