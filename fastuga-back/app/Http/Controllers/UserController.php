<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller // falta adicionar try and catch e DB neste
{

    public function showAllUsers()
    {
        return UserResource::collection(User::paginate(20));
    }

    public function showUsersByEmail($email)
    {
        $query = DB::table('users')->where('email','LIKE','%'.$email.'%')->paginate(20);
        return UserResource::collection($query);
    }


    public function showUsersByType($type)
    {
        $users = null;
        switch ($type) {
            case 'chef':
                $users = User::where('type','EC')->paginate(20);
                break;
            case 'delivery':
                $users = User::where('type','ED')->paginate(20);
                break;
            case 'manager':
                $users = User::where('type','EM')->paginate(20);
                break;
            default:
                return response()->json(['message' => 'Invalid user type!'],400);
                break;
        }
        return UserResource::collection($users);
    }

    public function showUsersByTypeAndEmail($email,$type)
    {
        $users = null;
        switch ($type) {
                case 'chef':
                    $users = DB::table('users')->where('type', 'EC')
                    -> where('email','LIKE','%'.$email.'%')->paginate(20);
                    break;
                case 'delivery':
                    $users = DB::table('users')->where('type', 'ED')
                    -> where('email','LIKE','%'.$email.'%')->paginate(20);
                    break;
                case 'manager':
                    $users = DB::table('users')->where('type', 'EM') 
                    -> where('email','LIKE','%'.$email.'%')->paginate(20);
                    break;
                default:
                    return response()->json(['message' => 'Invalid user type!'],400);
                    break;
                    }
        return UserResource::collection($users);
    }

    

    public function showUser($id)
    {   
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    
    public function showMyself(Request $request) 
    {
        return new UserResource($request->user());
        /*return response()->json(
            ['msg' => 'test'],
            400
        );*/
        //return new UserResource(Auth::user());
    }
    

    // this function cannot be used to sign up customers 
    public function signUpUser(UserRequest $request)
    {

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->type = $request->input('type');
        $user->blocked = 0;
        $user->photo_url = $request->input('photo_url');

        if( $user->save() ){
            return new UserResource($user);
        }
    }

    // this function cannot be used to edit customers 
    public function editUserProfile(UserRequest $request, $id)
    {
        $user = User::findOrFail( $request->id );
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->type = $request->input('type');
        $user->blocked = $request->input('blocked');
        $user->photo_url = $request->input('photo_url');

        if( $user->save() ){
            return new UserResource($user);
        }
    }

    public function deleteUserAccount($id)
    {
        $user = User::findOrFail( $id );
        if( $user->delete() ){
            return new UserResource( $user );
        }
    }

    public function blockUserAccount($id)
    { 
        if (Auth::user()->type != "EM"){
            return response()->json(['message' => 'The current logged user is not an Employee Manager'],400);
        }

        try{
            DB::beginTransaction();

            $user = User::findOrFail( $id );
            $user->blocked = "1"; // bloqueado

            $user->save();

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new UserResource($user);
    }
    
    /*
    public function blockUserAccount($id)
    {
        DB::beginTransaction();

            $user = User::findOrFail( $id );
            $user->blocked = "1"; // bloqueado

            $user->save();

            DB::commit();
    }
    */
}
