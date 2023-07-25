<?php

namespace Database\Seeders;

use App\Models\Outlets;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Outlets::create([
            'outlet_id' => 1000000,
            'name' => 'Main Outlet',
            'city' => '1',
            'state' => '1',            
            'created_by' => 1,
        ]);
    }
}
