<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;

use App\Http\Resources\UserResource;
use App\Mail\OtpMail;
use App\Models\PasswordOtp;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $extension = $image->getClientOriginalExtension();

            $imageName = 'user_' . uniqid() . '.' . $extension;

            $image->move(public_path('img/users'), $imageName);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'image' => $imageName,
            'role' => 'user',
            'status' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registered Successfully.',
            'token' => $token,
            'data' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->status) {

            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive.',
            ], 403);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Successfully.',
            'token' => $token,
            'data' => new UserResource($user),
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($request->user()),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $imageName = $user->image;

        if ($request->hasFile('image')) {

            if ($user->image && File::exists(public_path('img/users/' . $user->image))) {
                File::delete(public_path('img/users/' . $user->image));
            }

            $image = $request->file('image');

            $extension = $image->getClientOriginalExtension();

            $imageName = 'user_' . uniqid() . '.' . $extension;

            $image->move(public_path('img/users'), $imageName);
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imageName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {

            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully. Please login again.',

        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        if ($user->image && File::exists(public_path('img/users/' . $user->image))) {

            File::delete(public_path('img/users/' . $user->image));
        }

        $user->tokens()->delete();

        $user->delete();

        return response()->json([

            'success' => true,

            'message' => 'Account deleted successfully.',

        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Email not found.',
            ], 404);
        }

        $oldOtp = PasswordOtp::where('email', $request->email)->first();

        if ($oldOtp && $oldOtp->created_at->diffInSeconds(now()) < 60) {

            return response()->json([
                'success' => false,
                'message' => 'Please wait 60 seconds before requesting another code.',
            ], 429);
        }

        PasswordOtp::where('email', $request->email)->delete();

        $otp = rand(100000, 999999);

        PasswordOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully.',
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $otp = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otp) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.',
            ], 400);
        }

        if ($otp->expires_at->isPast()) {

            $otp->delete();

            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code is valid.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $otp = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otp) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.',
            ], 400);
        }

        if ($otp->expires_at->isPast()) {

            $otp->delete();

            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired.',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $otp->delete();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}
