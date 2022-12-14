<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        //dd($user);
        if ($user != null && ($user->blocked == "1" || $user->deleted_at != null)) {
            return response()->json(
                ['msg' => 'User is blocked'],
                403
            );
        }

        request()->request->add([
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username' => (string)$request->email,
            'password' => (string)$request->password,
            'scope'         => '',
        ]);

        $url = env('PASSPORT_SERVER_URL') . '/oauth/token';

        $request = Request::create($url, 'POST');
        $response = Route::dispatch($request);

        $errorCode = $response->getStatusCode();

        if ($errorCode == '200') {
            return json_decode((string) $response->content(), true);
        } else {
            return response()->json(
                ['msg' => 'User credentials are invalid'],
                $errorCode
            );
        }
    } 

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
