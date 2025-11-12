<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        $user = User::create($request->toArray());
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ], Response::HTTP_CREATED);
    }

    public function index()
    {
        $users = User::all(['id','name','email', 'role']);
        if(count($users) == 0) {
            return response()->json(['message' => 'No items stored'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($users, Response::HTTP_OK);
    }

    public function get($id)
    {
        $user = User::find($id, ['id', 'name', 'email', 'role']);
        if(empty($user)) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user, Response::HTTP_OK);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'max:255',
            'email' => "max:255|email|unique:users,email,$id",
            'password' => ['string', Password::defaults(), 'confirmed'],
            'role' => [Rule::enum(Role::class)],
        ]);

        $user = User::find($id);
        if(empty($user)) {
            return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
        }

        if(isset($request->name)) {
            $user->name = $request->name;
        }
        if(isset($request->email)) {
            $user->email = $request->email;
        }
        if(isset($request->password)) {
            $user->password = $request->password;
        }
        if(isset($request->role)) {
            $user->role = $request->role;
        }
        $user->save();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ], Response::HTTP_OK);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if($user) {
            $user->delete();
            return response()->json(['message' => "$id deleted"], Response::HTTP_OK);
        }
        return response()->json(['message' => 'ID not found'], Response::HTTP_NOT_FOUND);
    }

    public function login(Request $request){
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'], $user->password)){
            return response()->json(['message' => 'Invalid Credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken(
            $user->name.'-AuthToken',
            ['users'],
            now()->addMinutes(15)
        )->plainTextToken;
        return response()->json(['access_token' => $token]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
