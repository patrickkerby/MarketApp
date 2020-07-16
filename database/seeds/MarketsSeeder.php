<?php

use Illuminate\Database\Seeder;

class MarketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('markets')->delete();
        $json = File::get("database/data/markets.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          App\Markets::create(array(
            'id' => $obj[0],
            'created_at' => $obj[6],
            'updated_at' => $obj[7],
            'name' => $obj[1],
            'city' => $obj[3],
            'postal_code' => $obj[5],
            'province' => $obj[4],
            'street_address' => $obj[2]
          ));
        }
    }
}