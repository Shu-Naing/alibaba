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
            'outlet_id' => MAINOUTLETID,
            'name' => 'Main Outlet',
            'city' => '',
            'state' => '',
            'created_by' => 1,
        ]);

        Outlets::create([
            'outlet_id' => BODID,
            'name' => 'BOD',
            'city' => '',
            'state' => '',
            'created_by' => 1,
        ]);
        
        Outlets::create([
            'outlet_id' => DEPID,
            'name' => 'Department',
            'city' => '',
            'state' => '',
            'created_by' => 1,
        ]);
    }
}
