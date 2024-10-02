<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Module;

use Validator;
use Illuminate\Http\JsonResponse;

class ModuleController extends BaseController
{
    public function get_modules(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'page' => 'required',
            'page_size' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $details = $request->only(['page_size']);
        $query = Module::query();
        $query->where([['is_active', true], ['delete_flag', false]]);
        $data = $query->paginate($details['page_size']);

        $success = true;
   
        return $this->sendResponse($data, 'Modules fetched successfully.');
    }
}
