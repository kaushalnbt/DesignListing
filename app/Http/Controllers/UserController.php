<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->middleware('auth:api'); // Add this line
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        try {
            $users = User::all();
            return $this->apiResponse->sendResponse(200, "Users fetched successfully!", $users);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "User created successfully!", $user);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show(User $user)
    {
        try {
            return $this->apiResponse->sendResponse(200, "User fetched successfully!", $user);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $user->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "User updated successfully!", $user);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "User deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }
}
