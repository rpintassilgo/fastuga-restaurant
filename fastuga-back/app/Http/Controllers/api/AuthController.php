<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Validator;
// criar modelo authUser e auth

class AuthController extends Controller
{
    
    public function login(Request $request){
        request()->request->add ([
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username' => (string)$request->email,
            'password' => (string)$request->password,
            'scope'    => '',
        ]);
        
        $url = env('PASSPORT_SERVER_URL') . '/oauth/token';          
        
        $request = Request::create($url, 'POST');
        $response = Route::dispatch($request);   
        
        $errorCode = $response->getStatusCode();         
        
        if ($errorCode == '200') {
            return json_decode((string) $response->content(), true);
        } else {
            return response()->json(['msg' => 'User credentials are invalid'],$errorCode);
        }
    }

    public function myself(){
        return new User(Auth::user());
    }

    public function logout(Request $request) {
        $accessToken = $request->user()->token();

        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();

        return response(['msg' => 'Token revoked'], 200);
    }

  /*  public function getAllUsers(){
        $users = AuthUser::all();

        return VcardViewAdminResource::collection($users);
    } 
    
    public function editProfile(EditProfileRequest $request){

        switch (Auth::user()->user_type) {
            case 'EM':
                $loggedId = Auth::user()->id;
                $user = User::where()->find($loggedId);
                break;
            case 'V':
                $loggedId = Auth::user()->username;
                $user = Vcard::find($loggedId);
                break;
        }

        $transactionStatus = DB::transaction(function() use($request,$user){
            if($request->password || $request->confirmation_code) {
                if(!Hash::check($request->current_password, $user->password)) {
                    return response()->json(
                        ['message' => 'current password is incorrect'],
                        400
                    );
                }
            }
            if($request->name){
                $user->name = $request->name;
            }


            if($request->password){
                $user->password = bcrypt($request->password);
            }

            if($request->email){
                $user->email = $request->email;
            }

            if ($request->hasFile('photo_url')) {
                $path = $request->photo_url->store('public/fotos');
                $user->photo_url = basename($path);
            }

            if($request->confirmation_code){
                $user->confirmation_code = bcrypt($request->confirmation_code);
            }

            $user->update();
            return null;

        });

        // outside DB:transaction because transaction needs to commit
        return $transactionStatus ? $transactionStatus : new AuthUserResource(AuthUser::find($loggedId));
    }

    public function addAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'There were validation errors','errors' => $validator->getMessageBag()],422);
        }
        try {
            DB::beginTransaction();
            $newAdmin = new User;
            $newAdmin->name = $request->name;
            $newAdmin->email = $request->email;
            $newAdmin->password = bcrypt($request->password);
            $newAdmin->save();
            DB::commit();
        }
        catch(\Throwable $th){
            DB::rollBack();
            return response()->json(['message' => 'Internal server Error','error' => $th->getMessage()],500);
        }
        return new AuthUserResource($newAdmin);
    }
    */

}