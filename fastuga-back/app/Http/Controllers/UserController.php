<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller // falta adicionar try and catch e DB neste
{

    public function index()
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


    public function show($id)
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
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail( $request->$id );
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

    public function blockUser(UserRequest $request)
    {
        $user = User::findOrFail( $request->id );
        $user->blocked = 1;

        if( $user->save() ){
            return new UserResource($user);
        }
    }

    public function unblockUser(UserRequest $request)
    {
        $user = User::findOrFail( $request->id );
        $user->blocked = 0;

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

    public function uploadUserImage(ImageRequest $request){
        $requestData = $request->validated();

        if($requestData['photo_file']){
            $nameString = Carbon::now()->format('Ymd_His') . '_' . $requestData['photo_file']->getClientOriginalName();
            $path = $requestData['photo_file']->storeAs('public/fotos/', $nameString);
           // $product->photo_url = $nameString;

        }
        //$product->save();

        return (string) $nameString;
    }

}
