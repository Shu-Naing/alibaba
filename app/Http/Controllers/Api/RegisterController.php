<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\MocOwcUser;
use App\Models\MocOwcUserInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;

class RegisterController extends BaseController
{    
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:moc_owc_users,username',
            'email' => 'required|email|unique:moc_owc_users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $appname = 'moci-owc-application';
   
        $input = $request->only('username','name','email','password');
        $input['password'] = Hash::make($input['password']);  
       
        $user = MocOwcUser::create($input);

        $info_input = $request->only('contact_no','packages','township','address');
        $info_input['user_id'] = $user->id;

        $user_info = MocOwcUserInfo::create($info_input);

        $success['token'] =  $user->createToken($appname)->plainTextToken;
        $success['id'] =  $user->id;
        $success['name'] =  $user->name;
        $success['username'] =  $user->username;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = MocOwcUser::where('username',$request->username)->first();

        if($user && Hash::check($request->password,$user->password) && $user->status){
            $appname = 'moci-owc-application';
            $success['token'] =  $user->createToken($appname)->plainTextToken;
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            $success['username'] =  $user->username;
   
            return $this->sendResponse($success, 'User login successfully.');
        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], '403');
        }
    }

    public function deactivate(Request $request){   

        $user = MocOwcUser::find($request->id);

        if($user){
            $success = array(); 
            $user->update(['status'=>0]);
            return $this->sendResponse($success, 'User deactivate successfully.');
        }else{
            return $this->sendError('User does not exist.', ['error'=>'User does not exist'], '405');
        }
    }
}
?>