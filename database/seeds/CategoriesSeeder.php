<?php

use App\categories;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        $json = File::get("database/data/categories.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          App\categories::create(array(
            'id' => $obj[0],
            'created_at' => $obj[3],
            'updated_at' => $obj[4],
            'name' => $obj[1],
            'desc' => $obj[2]
          ));
        }
    }
}