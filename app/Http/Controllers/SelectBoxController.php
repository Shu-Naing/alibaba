<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SelectBoxController extends Controller
{
    public function getData()
    {
        $dummyDataPath = public_path('/dummy_data.json');
        $dummyData = json_decode(file_get_contents($dummyDataPath));

        return response()->json($dummyData);
    }
}
