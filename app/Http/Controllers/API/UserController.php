<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function add_role(Request $request) {
        $user = $request->user();
        $user->addRole('admin');

        return $this->sendResponse($user, 'User details fetched successfully.');
    }
}
