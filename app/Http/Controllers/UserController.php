<?php

namespace App\Http\Controllers;

use App\Mail\Confirmation;
use App\Mail\Reset;
use App\Models\education;
use App\Models\JobExperience;
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
            'email' => 'required|email|unique:users,email',
            'phone_number' => ['required', 'unique:users,phone_number', new Phone],
            'password' => 'required|string',

        ]);
        if ($validate->fails()) {
            return $this->sendResponse($validate->errors()->all(), null, 400);
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
        } catch (Exception $e) {
            return $this->sendResponse($e->getMessage(), null, 400);
        }
        Notification::send($user, new NewUserNotification($user, $user->confirmation_code));
//        Termii::send($user->phone_number, 'WElcome Aboard');
        return $this->sendResponse('Account created successfully, proceed to login', null, 201);
    }

    public function confirmEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:user.email',
            ]);


            $user = auth()->user();
            if ($user->email !== $request->email) return $this->sendResponse('Please enter a correct email address', null, 400);
//            return $this->sendResponse('kkk', $user, 200);

            if (!$user) {
                return $this->sendResponse('User not found', null, 404);
            }

            if ($user->hasVerifiedEmail()) {
                return $this->sendResponse('Email already verified', null, 403);
            }
            $user->confirmation_code = rand(10000, 99999);
            $user->save();

            Notification::send($user, new ConfirmNotification($user, $user->confirmation_code));

            return $this->sendResponse('Confirmation code sent successfully', null, 200);
        } catch (Exception $e) {
            return $this->sendResponse($e->getMessage(), $user, 500);
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
                try {
                    sendSms($user->phone_number, 'Email verified');
                } catch (Exception $e) {
                    return $this->sendResponse($e->getMessage(), null, 400);
                }

            }

            return $this->sendResponse('Email verified successfully', null, 200);
        } else {
            return $this->sendResponse('Incorrect code', null, 400);
        }
    }

    //Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email or Password Incorrect',
                'data' => null
            ], 401);
        }
        $user = $request->user();
        $token = $user->createToken('access-token')->plainTextToken;
        return $this->sendResponse('Welcome aboard', $token, 200);
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
        $authUser = auth()->user()->load('experience', 'education');

        return $this->sendResponse('User returned successfully', $authUser, 200);
    }

    public function getUserListing()
    {
        $user = User::whereId(auth()->id())->first();
        $userPost = $user->thejob;
        if ($userPost === null) return $this->sendResponse('User has no jobs', null, 400);
        return $this->sendResponse('User jobs returned successfully', $userPost, 200);
    }

    public function updateEducation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'degree' => 'required|string|max:255|in:B.tech,B.Sc,M.Sc,SSCE,Ond,Hnd,Phd',
            'school' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date_type' => 'required|in:present,specific_date',
            'end_date' => $request->end_date_type === 'specific_date' ? 'required_if:end_date_type,specific_date|date' : '',
        ]);


        if ($validator->fails()) return $this->sendResponse(implode(',', $validator->errors()->all()), null, 400);
        $user = auth()->user();
        try {
            education::create([
                'user_id' => $user->id,
                'degree' => $request->degree,
                'school' => $request->school,
                'course' => $request->course,
                'start_date' => $request->start_date,
                'end_date_type' => $request->end_date_type,
                'end_date' => $request->end_date
            ]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse('Education added successfully', null, 201);
    }

    public function updateExperience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string',
            'company' => 'required|string',
            'start_date' => 'required|date',
            'end_date_type' => 'required|in:present,specific_date',
            'end_date' => $request->end_date_type === 'specific_date' ? 'required_if:end_date_type,specific_date|date' : '',
            'job_description' => 'required|string',
            'job_type' => 'required|string|in:remote,hybrid,onsite',
            'job_mode' => 'required|string|in:internship,contract,full-time'
        ]);

        if ($validator->fails()) return $this->sendResponse(implode(',', $validator->errors()->all()), null, 400);
        $user = auth()->user();
        try {
            JobExperience::create([
                'user_id' => $user->id,
                'job_title' => $request->job_title,
                'company' => $request->company,
                'job_description' => $request->job_description,
                'start_date' => $request->start_date,
                'end_date_type' => $request->end_date_type,
                'end_date' => $request->end_date,
                'job_type' => $request->job_type,
                'job_mode' => $request->job_mode,

            ]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse('Experience added successfully', null, 201);

    }

    public function ResetPassword(Request $request)
    {
        try {
            $input = $request->validate([
                'email' => 'required|email',
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
        $user = auth()->user();

        if ($request->has('email')) {
            return $this->sendError('Email cannot be modified');
        }
        if ($request->has('phone_number')) {
            return $this->sendError('Phone number cannot be modified');
        }
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        }

        if ($request->hasFile('cv')) {
            $uploadedFileUrl = Cloudinary::uploadFile($request->file('cv')->getRealPath())->getSecurePath();
            $data['cv'] = $uploadedFileUrl;
        }
        $user->update($data);

        return $this->sendResponse('Updated successfully', null, 200);
    }

    public function notAuth()
    {
        $this->sendResponse('User not authenticated', null, 401);
    }
}
