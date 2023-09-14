<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccessController extends Controller
{
    use ThrottlesLogins,AuthenticatesUsers;
    public function register(Request $request)
    {
        //validation
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','max:255','unique:users'],
            'password' => ['required','string','min:8','confirmed'],
        ]);
        try {
            //creates a new user
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
            //generates an access token for this user
            $token = $user->createToken('andrew')->plainTextToken;
            $userData = [
                'token' => $token,
                'user' => $user
            ];
            return response()->json(['user' => $userData],201);
        } catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json(['message' => $exception->getMessage(),$exception->getCode()]);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('email',$request['email'])->first();
        if ($user && Hash::check($request['password'],$user->password))
        {
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('andrew')->plainTextToken
            ],200);
        } else{
            return response()->json(['message' => 'Password or email is incorrect'],404);
        }
    }
}
