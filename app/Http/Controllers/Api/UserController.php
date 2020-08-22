<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

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
            'name' => $request->name,
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
