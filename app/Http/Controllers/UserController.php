<?php

namespace App\Http\Controllers;

use App\Mail\Confirmation;
use App\Mail\Reset;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //Create nwe user
    public function createUser(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'image' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'data' => null,
            ], 400);
        }
        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        }
        $data['password'] = Hash::make($request->password);
        $data['confirmation_code'] = rand(22222, 99999);
        $CreateUser = User::create($data);
        $token = $CreateUser->createToken('access-token')->accessToken;
        Mail::send(new Confirmation($CreateUser));

        return response()->json([
            'message' => 'User created successfully',
            'data' => $CreateUser,
            'token' => $token,
            'error' => false,
        ], 200);
    }

    //verify email address
    public function verifyEmail(Request $request)
    {
        try {
            $input = $request->validate([
                'confirmation_code' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
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
        if ($input['confirmation_code'] == $user->confirmation_code) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return response()->json([
                'message' => 'Email verified successfully',
                'data' => $user,
                'error' => false,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email not verified, invalid confirmation code',
                'data' => null,
                'error' => true,
            ], 401);
        }
    }

    //Login
    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true,
                'data' => null,
            ], 400);
        }

        $check = Auth::attempt($data);

        if (! $check) {
            return response()->json([
                'message' => 'User not found',
                'error' => true,
                'data' => null,
            ], 400);
        } else {
            $User = User::where('email', $data['email'])->first();
            $token = $User->createToken('access-token')->accessToken;

            return response()->json([
                'message' => 'Login successful',
                'data' => $User,
                'token' => $token,
                'error' => false,
            ]);
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
}
