<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillingInformations;
use App\Models\StoreSetting;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Auth;

class StroeController extends Controller
{
    public function storeSettingIndex()
    {
        $store = StoreSetting::select('name', 'phone_number', 'email', 'address')->get();

        if ($store->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $store
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No promotions found'
            ]);
        }                            

        return response()->json(['data' => $result], 200);
    }
    public function storeSettingAdd(Request $request)
    {
        $name = $request->input('name');
        $phone_number = $request->input('phone_number');
        $email = $request->input('email');
        $address = $request->input('address');

        
        $storeSetting = new StoreSetting;
        $storeSetting->name = $name;
        $storeSetting->phone_number = $phone_number;
        $storeSetting->email = $email;
        $storeSetting->address = $address;
        $storeSetting->save();

        $response = [
            'status' => 200,
            'description' => 'Create store setting successfully.',
            // 'data' => [
            //     'id' => $billing->id,
            //     'township' => $billing->township,
            //     'price' => $billing->price
            // ]
        ];

        return response()->json($response);
    }
    public function storeSettingEdit(Request $request, $id)
    {
        $storeSetting = StoreSetting::find($id);

        if (!$storeSetting) {
            $response = [
                'status' => 404,
                'description' => 'Store setting update successfully.'
            ];

            return response()->json($response);
        }

        $data = $request->only(['name', 'phone_number', 'email', 'address']);

        $storeSetting->update($data);

        $response = [
            'status' => 200,
            'description' => 'Billing information successfully updated.',
            // 'data' => [
            //     'id' => $billing->id,
            //     'township' => $billing->township,
            //     'price' => $billing->price
            // ]
        ];

        return response()->json($response);
    }
    public function add(Request $request)
    {
        $name = $request->input('name');
        $account_number = $request->input('account_number');

        $existingBill = BillingInformations::where('account_number', $account_number)->first();

        if ($existingBill) {
            $response = [
                'status' => 409,
                'description' => 'Billing information already exists for this account number.',
                // 'data' => [
                //     'township' => $existingbilling->township,
                // ]
            ];

            return response()->json($response);
        } else {
            $billing = new BillingInformations;
            $billing->name = $name;
            $billing->account_number = $account_number;
            $billing->save();

            $response = [
                'status' => 200,
                'description' => 'Billing information successfully added.',
                // 'data' => [
                //     'id' => $billing->id,
                //     'township' => $billing->township,
                //     'price' => $billing->price
                // ]
            ];

            return response()->json($response);
        }
    }
    public function edit(Request $request, $id)
    {
        $billing = BillingInformations::find($id);

        if (!$billing) {
            $response = [
                'status' => 404,
                'description' => 'Billing information not found.'
            ];

            return response()->json($response);
        }

        $data = $request->only(['name', 'account_number']);

        $billing->update($data);

        $response = [
            'status' => 200,
            'description' => 'Billing information successfully updated.',
            // 'data' => [
            //     'id' => $billing->id,
            //     'township' => $billing->township,
            //     'price' => $billing->price
            // ]
        ];

        return response()->json($response);
    }
    public function delete($id)
    {
        $billing = BillingInformations::find($id);

        if (!$billing) {
            $response = [            
                'status' => 404,            
                'description' => 'Billing information not found.'        
            ];

            return response()->json($response);
        }

        $billing->delete();

        $response = [
            'status' => 200,        
            'description' => 'Billing information successfully deleted.',    
        ];

        return response()->json($response);
    }

}
