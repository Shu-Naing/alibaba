<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryInformations;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Auth;

class DeliInfoController extends Controller
{
    public function index()
    {
        $delis = DeliveryInformations::select('township', 'price')->get();

        if ($delis->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $delis
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Delivery Informations not found'
            ]);
        }                            

        return response()->json(['data' => $result], 200);
    }

    public function add(Request $request)
    {
        $township = $request->input('township');
        $price = $request->input('price');

        $existingDelivery = DeliveryInformations::where('township', $township)->first();

        if ($existingDelivery) {
            $response = [
                'status' => 409,
                'description' => 'Delivery information already exists for this township.',
                // 'data' => [
                //     'township' => $existingDelivery->township,
                // ]
            ];

            return response()->json($response);
        } else {
            $delivery = new DeliveryInformations;
            $delivery->township = $township;
            $delivery->price = $price;
            $delivery->save();

            $response = [
                'status' => 200,
                'description' => 'Delivery information successfully added.',
                // 'data' => [
                //     'id' => $delivery->id,
                //     'township' => $delivery->township,
                //     'price' => $delivery->price
                // ]
            ];

            return response()->json($response);
        }
    }
    public function edit(Request $request, $id)
    {
        $delivery = DeliveryInformations::find($id);

        if (!$delivery) {
            $response = [
                'status' => 404,
                'description' => 'Delivery information not found.'
            ];

            return response()->json($response);
        }

        $data = $request->only(['city', 'township']);

        $delivery->update($data);

        // $township = $request->input('township');
        // $price = $request->input('price');

        // $delivery->township = $township;
        // $delivery->price = $price;
        // $delivery->save();

        $response = [
            'status' => 200,
            'description' => 'Delivery information successfully updated.',
            // 'data' => [
            //     'id' => $delivery->id,
            //     'township' => $delivery->township,
            //     'price' => $delivery->price
            // ]
        ];

        return response()->json($response);
    }

}
