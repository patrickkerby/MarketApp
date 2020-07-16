<?php

use Illuminate\Database\Seeder;

class ProductQuantitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_quantities')->delete();
        $json = File::get("database/data/product_quantities.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          App\product_quantities::create(array(
            'id' => $obj[0],
            'created_at' => $obj[5],
            'updated_at' => $obj[6],
            'market_day_id' => $obj[1],
            'packed' => $obj[3],
            'product_id' => $obj[2],
            'returned' => $obj[4]
          ));
        }    
    }
}