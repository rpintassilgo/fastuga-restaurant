<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $user = new User;
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

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        $user = User::findOrFail( $id );
        if( $user->delete() ){
            return new UserResource( $user );
        }
    }

}
