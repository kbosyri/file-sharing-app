<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validate = Validator::make($request->all(),[
            'email' => 'required',
            'name' => 'required',
            'password' => 'required',
            'confirmpassword'=>'required'
        ]);

        if($validate->fails())
        {
            return response()->json(['massege' => 'Some Values Have Not Been sent','json'=>$request->all()],400);
        }

        if($request->password != $request->confirmpassword)
        {
            return response()->json(['message'=>'password And Confrm Password Do Not Match'],400);
        }

        if(User::where('email',$request->email)->exists())
        {
            return response()->json(['massege' => 'This E-mail Already Exists'],400);
        }

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password); 
        $user->save();

        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validate->fails())
        {
            return response()->json(['massege' => 'Validation Failed'],400);
        }

        $user = User::where('email',$request->email)->first();
        if(!Auth::attempt(['email'=> $request->email,'password'=>$request->password],true))
        {
            return response()->json(['massege' => 'user not found','user'=> new UserResource($user)],400);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user'=>new UserResource($request->user()),
            'token'=>$token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'user'=>new UserResource($request->user()),
            'message'=>'token deleted'
        ]);
    }
}
