<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    /**
     * Logged userdetail api
     *
     * @return \Illuminate\Http\Response
     */
    public function get_user_details(Request $request): JsonResponse
    {
        $user = $request->user();
        $role = $user->roles[0];

        $response = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $role->name,
            'role_display' => $role->display_name,
            'img_url' => "ippothaiku illa future la varum wait pannunga. Nambathaan!url naale nambathaan!"
        ];
   
        return $this->sendResponse($response, 'User details fetched successfully.');
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
     
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
     
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if($user) {
            $res = $user->currentAccessToken()->delete();
            return $this->sendResponse($res, 'User logout successfully.'); 
        } else {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    /**
     * Logout All api
     *
     * @return \Illuminate\Http\Response
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $user = $request->user();
        if($user) {
            $res = $user->tokens()->delete();
            return $this->sendResponse($res, 'User logout from all devices.'); 
        } else {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
