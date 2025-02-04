<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class ApiResponse extends Controller
{
    public function sendResponse($code, $message, $data)
    {
        if ($code == 200 || $code == 201) {
            return response([
                'status' => 'success',
                'status_code' => $code,
                'message' => $message,
                'data' => $data
            ], $code);
        } else {
            try {
                $requestData = Request::except('password');
            } catch (\Throwable $e) {
                Log::error('Error dispatching LogActivity: ' . $e->getMessage());
            }

            return response([
                'status' => 'error',
                'status_code' => $code,
                'message' => $message,
                'data' => $data,
            ], $code);
        }
    }
}