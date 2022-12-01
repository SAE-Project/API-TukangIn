<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            "password_confirmation" => "required|same:password",
        ]);
        if($validator->fails()){
            return response()->json(
                [
                    "status"=>false,
                    "message"=>"Ada Kesalahan",
                    "data"=>$validator->errors()
                ]
            );
        }

        $input = $request -> all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            "message" => "sukses",
            "data" => $success
        ]);
    }

    public function login(Request $request){
        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['id'] = $auth->id;
            return response()->json([
                'success'=> true,
                "message" => "berhasil login",
                "data" => $success
            ],200);
        }else{
            return response()->json([
                'success'=> false,
                "message" => "Email atau password salah",
                "data"=>null
            ]);
        }
    }
}
