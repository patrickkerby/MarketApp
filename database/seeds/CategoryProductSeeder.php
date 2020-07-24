<?php

use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_product')->delete();
        $json = File::get("database/data/category_product.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          DB::table('category_product')->insert(array(
            'product_id' => $obj->id,
            'category_id' => $obj->category_id 
          ));
        }
    }
}