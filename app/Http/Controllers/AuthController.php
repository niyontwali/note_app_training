<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Raw;

class AuthController extends Controller {
    /**
     * Register a user
     */
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' =>'sometimes|in:user,admin'
        ]);

        // Hash password
        $fields['password'] = Hash::make($fields['password']);

        // create a new user
        $newUser = User::create($fields);

        // generate totken
        $token = $newUser -> createToken('auth-token');

        return response() -> json([
            'ok' => true,
            'message' => 'User created successfully',
            'token' => $token->plainTextToken
        ], 201);
    }

     /**
     * login a user
     */
    public function login(Request $request) {
        // validate fields
        $fields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // find a user corresponding to the email specified (email:unique)
        $user = User::where('email', $request->email)->first();
   
        // $user = User::where('email', $fields['email'])->first(); // when using the field

        // Return error to user in case user not found
        if(!$user || !Hash::check($request->password,$user -> password)) {
            return response() -> json([
                'ok' => false,
                'message' => 'Invalid Credentials'
            ]);
        }

        // delete old tokens
        $user->tokens()->delete();

        // assign token to the user
        $token = $user->createToken('auth-token');

    return response()->json([
        'ok' => true,
        'token' => $token->plainTextToken
    ]);
    }

     /**
     * logout a user
     */
    public function logout(Request $request) {
        // get current logged in user and delete the token
        $request -> user() -> currentAccessToken()-> delete();

        return response()->json([
            'ok' => true,
            'message' => 'Logout successfully'
        ]);
    }

}

