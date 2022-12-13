<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller // falta adicionar try and catch e DB neste
{

    public function showAllUsers()
    {
        return UserResource::collection(User::paginate(20));
    }

    public function showAllChefEmployees() 
    {
        $chefEmployees = User::where('type','EC')->paginate(20);
        return UserResource::collection($chefEmployees);
    }

    public function showAllDeliveryEmployees() 
    {
        $deliveryEmployees = User::where('type','ED')->paginate(20);
        return UserResource::collection($deliveryEmployees);
    }

    public function showAllManagerEmployees() 
    {
        $managerEmployees = User::where('type','em')->paginate(20);
        return UserResource::collection($managerEmployees);
    }


    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function showMe(Request $request) // maybe use a specific request later
    {
        return new UserResource($request->user());
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

}
