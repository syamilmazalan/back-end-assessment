<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  $email
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        $user = User::where('email', $email)->first();

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $email
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $email)
    {
        $user = User::where('email', $email)->first();

        $user->update([
            'name' => $request->name
        ]);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy($email)
    {
        $user = User::where('email', $email)->first();

        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ]);
    }
}
