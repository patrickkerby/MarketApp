<?php

use Illuminate\Database\Seeder;

class MarketDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('market_days')->delete();
        $json = File::get("database/data/market_days.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          App\market_days::create(array(
            'id' => $obj[0],
            'market_id' => $obj[2],
            'created_at' => $obj[9],
            'updated_at' => $obj[10],
            'date' => $obj[1],
            'employee' => $obj[5],
            'load_at' => $obj[3],
            'setup_at' => $obj[4],
            'packing_notes' => $obj[7],
            'market_notes' => $obj[8],
            'admin_notes' => $obj[6],
            'estimated_revenue' => $obj[12],
            'actual_revenue' => $obj[13],
            'state' => $obj[11]
          ));
        }
    }
}