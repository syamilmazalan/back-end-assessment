<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation
        $valid = validator($request->only('email', 'name', 'password'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($valid->fails()) {
            $jsonError = response()->json($valid->errors()->all());
            return Response::json($jsonError, 400);
        }

        // Create User
        $data = request()->only('email','name','password');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Internal Request
        $client = Client::where('password_client', 1)->first();

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $data['email'],
            'password'      => $data['password'],
            'scope'         => null,
        ]);

        $token = Request::create(
            'oauth/token',
            'POST'
        );

        return Route::dispatch($token);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $client = Client::where('password_client', 1)->first();

            $request->request->add([
                'grant_type' => 'password',
                'username' => $request->email,
                'password' => $request->password,
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => ''
            ]);
    
            $token = Request::create(
                'oauth/token',
                'POST'
            );
    
            return Route::dispatch($token);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            if ($request->user()->token()->revoke()) {
                return response()->json([
                    'message' => 'Logged out successfully.'
                ]);
            }
        }
    }
}
