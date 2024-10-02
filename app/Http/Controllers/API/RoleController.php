<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends BaseController
{
    public function get_roles(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'page' => 'required',
            'page_size' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $details = $request->only(['page_size']);
        $query = Role::query();
        $query->where([['is_active', true], ['delete_flag', false]]);
        $data = $query->paginate($details['page_size']);

        $success = true;
   
        return $this->sendResponse($data, 'Roles fetched successfully.');
    }

    public function get_permissions(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'page' => 'required',
            'page_size' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $details = $request->only(['page_size']);
        $query = Permission::query();
        $query->where([['is_active', true], ['delete_flag', false]]);
        $data = $query->paginate($details['page_size']);

        $success = true;
   
        return $this->sendResponse($data, 'Roles fetched successfully.');
    }
}
