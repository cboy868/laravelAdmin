<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use App\Team;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user = Auth::user();




        $team = Team::where('name', 'guide')->first();
        $admin = Role::where('name', 'admin')->first();


        $team = Team::where('name', 'guide')->first();
        $editUser = Permission::where('name', 'edit-user')->first();

        $user->attachPermission($editUser, $team);


//        $user->attachRole($admin, $team);
//
//        dd($admin);
//        $user->attachRole($admin, $team);

//        $admin = Role::where('name', 'admin')->first();

//        $user = User::where('name', 'wansq')->first();
////        $user->attachRole($admin);
//
//
//
//        $a = $user->can('create-post');
//        dd($a);

//        return view('home');
    }
}
