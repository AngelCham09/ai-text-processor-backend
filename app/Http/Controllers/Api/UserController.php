<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();

        try {
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

            $user->sendEmailVerificationNotification();

            $token = $user->createToken('api-token')->plainTextToken;

            DB::commit();

            return ApiResponse::success('User registered successfully', [
                'token' => $token,
                'user' => new UserResource($user),
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return ApiResponse::error('Registration validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            DB::rollBack();
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
            'user' => new UserResource($user),
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

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'required_with:password|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return ApiResponse::success('Profile updated successfully', [
            'user' => new UserResource($user),
        ]);
    }
}
