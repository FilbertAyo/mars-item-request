<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Profile;

class AuthenticationController extends Controller
{

    public function login(Request $request)
    {
        try {
            // Validate the identifier
            $validateUser = Validator::make($request->all(), [
                'identifier' => 'required|string',  // assuming 'identifier' is the column name
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'error' => $validateUser->errors()
                ], 401);
            }

            // Retrieve the user by identifier
            $user = User::where('identifier', $request->identifier)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID does not  match any of our records'
                ], 401);
            }

            // Check if the user type is allowed
            if ($user->userType != 1 && $user->userType != 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized user',
                ], 401);
            }


            // Create and return the API token
            $token = $user->createToken("API_TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                "message" => "logged out succesfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getProfile(Request $request)
    {
        $user = $request->user(); // This gets the authenticated user

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $user, // Assuming the user is the profile
        ]);
    }



    public function deleteAccount(Request $request)
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Delete the user's profile
            $profile = User::where('email', $user->email)->first();
            if ($profile) {
                $profile->delete();
            }



            return response()->json([
                'status' => true,
                'message' => 'User account deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
