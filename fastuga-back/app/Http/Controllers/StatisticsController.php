<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function countUser($date) {
        $count = DB::table('users')
        ->whereYear('created_at', date($date))
        ->count();
        dd($count);
    return $count;
  }
}
