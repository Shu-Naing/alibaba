<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api;
// use App\Helpers\helper;
// use Helpers;

class CommonController extends Controller
{
    public function typeInfoApi(){
        $result =  curlApiGet(lOGINURL);
        return $result;
    }

    public function postApi(Request $request){
        return 'post';
    }

}
