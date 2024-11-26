<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function sendResponse($status = null, $message = null, $data = null, $http = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $http);
    }

    protected function errorResponse($status = null, $message = null, $http = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $http);
    }

    protected function messageResponse($status = null, $message = null, $http = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $http);
    }
}
