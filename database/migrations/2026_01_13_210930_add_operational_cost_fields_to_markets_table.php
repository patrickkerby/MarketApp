<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOperationalCostFieldsToMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->integer('typical_employees')->nullable()->after('name')->comment('Number of employees per market day');
            $table->decimal('typical_hours', 5, 2)->nullable()->after('typical_employees')->comment('Hours worked per market day');
            $table->decimal('avg_wage', 8, 2)->nullable()->after('typical_hours')->comment('Average wage per hour');
            $table->decimal('annual_stall_fee', 10, 2)->nullable()->after('avg_wage')->comment('Total stall/booth fees for the year');
            $table->decimal('annual_other_fees', 10, 2)->nullable()->after('annual_stall_fee')->comment('Total other fees/expenses for the year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->dropColumn([
                'typical_employees',
                'typical_hours',
                'avg_wage',
                'annual_stall_fee',
                'annual_other_fees'
            ]);
        });
    }
}
