<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse->sendResponse(422, $validator->errors(), null);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();

            return $this->apiResponse->sendResponse(201, "User registered successfully!", [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->apiResponse->sendResponse(401, "Unauthorized", null);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiResponse->sendResponse(200, "Login successful!", [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function user(Request $request)
    {
        return $this->apiResponse->sendResponse(200, "User fetched successfully!", $request->user());
    }

    public function logout(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->user()->tokens()->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Successfully logged out", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function refresh(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();

            return $this->apiResponse->sendResponse(200, "Token refreshed successfully!", [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function getTokenAndRefreshToken($email, $password)
    {
        $response = Http::asForm()->post(sprintf('%s/oauth/token', config('constants.misc.oauth_url')), [
            'grant_type' => 'password',
            'client_id' => config('constants.auth.passport.client_id'),
            'client_secret' => config('constants.auth.passport.client_secret'),
            'username' => $email,
            'password' => $password,
            'scope' => '*',
        ]);

        return $response;
    }

    public function check_auth(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->isGod()) {
                    $user['is_god'] = true;
                }
                return $this->apiResponse->sendResponse(200, 'User authenticated!', $user);
            }
            return $this->apiResponse->sendResponse(401, 'Failed', "Auth check failed");
        } catch (\Throwable $e) {
            return $this->apiResponse->sendResponse(500, $e->getMessage(), $e->getTraceAsString());
        }
    }
}
