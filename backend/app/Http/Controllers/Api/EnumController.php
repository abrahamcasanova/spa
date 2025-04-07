<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Enums\UserRoles;
use App\Http\Controllers\Controller;

class EnumController extends Controller
{
    /**
     * Get all statuses
     *
     * Returns a list of all statuses
     *
     * @return \Illuminate\Http\JsonResponse JSON response with statuses
     */
    public function status()
    {
        return response()->json(Status::all());
    }

    /**
     * Get all user roles
     *
     * Returns a list of all user roles
     *
     * @return \Illuminate\Http\JsonResponse JSON response with user roles
     */
    public function roles()
    {
        return response()->json(UserRoles::all());
    }
}
