<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class AuthController extends Controller
{
    private function passportAuthData($email,$password){
        return [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username' => (string)$email,
            'password' => (string)$password,
            'scope'         => ''
        ];
    }

    public function login(Request $request)
    {
        try {
            // check if user is blocked
            $user = User::where('email',$request->email)->first();
            if ($user != null && ($user->blocked == "1" || $user->deleted_at != null)) {
                return response()->json(['msg' => 'User is blocked'],403);
            }

            request()->request->add($this->passportAuthData($request->email,$request->password));
            $url = env('PASSPORT_SERVER_URL') . '/oauth/token';
            $request = Request::create($url, 'POST');
            $response = Route::dispatch($request);
            $errorCode = $response->getStatusCode();
            $auth_server_response = json_decode((string) $response->content(), true);
            return response()->json($auth_server_response, $errorCode);

        } catch (\Exception $e){
            return response()->json('Authentication has failed!', 401);
        }
    } 

    public function logout(Request $request)
    {
        //dd($request->user());
        //dd($request->user()->token());
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
