<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExcelImportRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\User;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\UserResource
     */
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @return App\Http\Resources\UserResource
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  $email
     * @return App\Http\Resources\UserResource
     */
    public function show($email)
    {
        $user = User::where('email', $email)->first();

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserRequest   $request
     * @param  $email
     * @return App\Http\Resources\UserResource
     */
    public function update(UserRequest $request, $email)
    {
        $user = User::where('email', $email)->first();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
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
    
        
    /**
     * Import excel file
     *
     * @param  App\Http\Requests\ExcelImportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function import(ExcelImportRequest $request)
    {
        Excel::import(new UsersImport, $request->users);

        return response()->json([
            'message' => 'Excel data imported.'
        ]);
    }
}
