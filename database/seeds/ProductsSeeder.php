<?php

use App\Products;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();
        $json = File::get("database/data/products.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          Products::create(array(
            'id' => $obj[0],
            'name' => $obj[1],
            'price' => $obj[2],
            'created_at' => $obj[3],
            'updated_at' => $obj[4],
            'category_id' => $obj[5]
          ));
        }
    }
}
