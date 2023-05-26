<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('outlets')->insert([
            'outlet_id' => Str::random(10),
            'name' => Str::random(10),
            'city' => Str::random(10),
            'state' => Str::random(10),
            'country' => Str::random(10)
        ]);
    }
}
