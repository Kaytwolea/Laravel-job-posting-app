<?php

namespace App\Http\Controllers;

use App\Mail\Confirmation;
use App\Mail\Reset;
use App\Models\User;
use App\Notifications\ConfirmNotification;
use App\Notifications\NewUserNotification;
use App\Rules\Phone;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{


    //Create nwe user
    public function createUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'user_name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => ['required', 'unique:users,phone_number', new Phone],
            'password' => 'required|string',

        ]);
        if ($validate->fails()) {
            return $this->sendResponse($validate->errors()->all(), null, 422);
        }
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        }

        $password = Hash::make($request->password);
        $code = rand(00000, 99999);
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->user_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => $password,
                'confirmation_code' => $code,
            ]);
            $token = $user->createToken('access-token')->plainTextToken;

        } catch (Exception $e) {
            return $this->sendResponse($e->getMessage(), null, 400);
        }
        Notification::send($user, new NewUserNotification($user, $user->confirmation_code));
        return $this->sendResponse('User created successfully', $token, 201);
    }

    public function confirmEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse($validator->errors()->all(), null, 422);
            }

            $user = auth()->user();

            if (!$user) {
                return $this->sendResponse('User not found', null, 404);
            }

            $user->email = $request->email;
            $user->confirmation_code = rand(10000, 99999);
            $user->save();

            Notification::send($user, new ConfirmNotification($user, $user->confirmation_code));

            return $this->sendResponse('Confirmation code sent successfully', null, 200);
        } catch (Exception $e) {
            return $this->sendResponse($e->getMessage(), null, 500);
        }
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse($validator->errors()->all(), null, 422);
        }
        $user = auth()->user();
        if ($user->hasVerifiedEmail()) {
            return $this->sendResponse('Email already verified', null, 403);
        }
        if ($request->code == $user->confirmation_code) {
            if ($user->markEmailAsVerified()) {
                $user->assignRole('employer');
                event(new Verified($user));

            }
            return $this->sendResponse('Email verified successfully', null, 200);
        } else {
            return $this->sendResponse('Incorrect code', null, 400);
        }
    }

    //Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        $this->sendResponse(implode(', ', $validator->errors()->all()), null, 400);
        try {
            $check = Auth::attempt($request->all());
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        if (!$check) {
            $this->sendResponse('Incorrect details', null, 400);
        } else {
            $User = User::where('email', $request['email'])->first();
            $token = $User->createToken('access-token')->plainTextToken;

            return $this->sendResponse('Logged in successfully', $token, 200);
        }
    }

    //Resend code
    public function Resendcode(Request $request)
    {
        try {
            $input = $request->validate([
                'email' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Enter a valid email address',
                'error' => true,
                'data' => null,
            ], 400);
        }
        $user = User::where('id', auth()->id())->first();
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified',
                'data' => null,
                'error' => false,
            ], 403);
        }
        $user->confirmation_code = rand(22222, 99999);
        $user->email = $input['email'];
        $user->save();
        Mail::send(new Confirmation($user));

        return response()->json([
            'message' => 'Resend code successful',
            'data' => $user,
            'error' => false,
        ], 200);
    }

    //Logout
    public function logout(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        auth()->user()->token()->revoke();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    //Get user with token on main page
    public function getUser(Request $request)
    {
        $authUser = auth()->user();

        return response()->json([
            'message' => 'User Returned Successfully',
            'data' => $authUser,
        ]);
    }

    public function getUserListing()
    {
        $user = User::whereId(auth()->id())->first();
        $userPost = $user->thejob;

        return response()->json([
            'message' => 'Posts returned successfully',
            'data' => $userPost,
            'error' => false,
        ], 200);
    }

    public function ResetPassword(Request $request)
    {
        try {
            $input = $request->validate([
                'email' => 'required',
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Enter a valid email address',
                'error' => true,
                'data' => null,
            ], 400);
        }

        $email = $input['email'];
        $user = User::where('email', $email)->first();
        $user['reset_code'] = rand(33333, 99999);
        $user->save();
        Mail::send(new Reset($user));

        return response()->json([
            'message' => 'Reset code was sent successfully',
            'error' => false,
        ], 200);
    }

    public function VerifyResetCode(Request $request)
    {
        try {
            $input = $request->validate([
                'reset_code' => 'required',
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Enter a valid reset code',
                'error' => true,
                'data' => null,
            ], 400);
        }

        $user = User::where('reset_code', $input['reset_code'])->first();
        $token = $user->createToken('access-token')->accessToken;

        return response()->json([
            'message' => 'Reset code was successfully confirmed',
            'token' => $token,
        ], 200);
    }

    public function ChangePassword(Request $request)
    {
        try {
            $input = $request->validate([
                'password' => 'required',
                'confirm_password' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'data' => null,
            ], 400);
        }

        $user = User::where('id', auth()->id())->first();
        if ($input['password'] == $input['confirm_password']) {
            $user->password = bcrypt($input['password']);
            $user->save();

            return response()->json([
                'message' => 'Password changed successfully',
                'data' => $user,
                'error' => false,
            ], 200);
        }
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        if ($request->has('email')) {
            return response()->json([
                'message' => 'Email cannot be changed',
                'error' => true,
            ], 401);
        }
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        }

        $user = User::where('id', auth()->id())->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user,
            'error' => false,
        ], 200);
    }

    public function notAuth()
    {
        $this->sendResponse('User not authenticated', null, 400);
    }
}
