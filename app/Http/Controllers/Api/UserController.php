<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        Log::info('Register action requested', [
            'name' => $request->name,
            'email' => $request->email,
        ]);

        try {
            //confirmed rule requires a field with _confirmation suffix
            //laravel will look for a field called password_confirmation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            return ApiResponse::success('User registered successfully', [
                'token' => $token,
                'user' => $user,
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::error('Registration validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'message' => $e->getMessage(),
            ]);
            return ApiResponse::error('Registration failed', null, 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Invalid email or password.', null, 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResponse::success('Login successful', [
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success('Logout successful');
    }

    public function profile(Request $request)
    {
        return ApiResponse::success('User profile fetched successfully', [
            'user' => new UserResource($request->user()),
        ]);
    }
}
