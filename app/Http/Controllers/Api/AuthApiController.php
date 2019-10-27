<?php
/**
 * Created by PhpStorm.
 * User: cleme
 * Date: 2019-10-27
 * Time: 6:59 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthApiController extends Controller
{
    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        if(!Auth::attempt($credentials))
            return response()->json([
                'success' => false,
                'message' => "Authentication failed",
                "user" => null
            ], 201);

        $user = $request->user();

        //Remove old token
        $old = DB::table('oauth_access_tokens')->where('user_id', $user->id);
        if($old){
            $old->delete();
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addDays(365);
        $token->save();

        return response()->json([
            'success' => true,
            'message' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'access_token' => $tokenResult->accessToken
            ]
        ], 201);
    }

    public function register(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        try {
            $user = User::create(request(['name', 'email', 'password']));
            \auth()->login($user);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'failed to register',
                'user' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Registered successfully",
            'user' => $user
        ]);
    }
}