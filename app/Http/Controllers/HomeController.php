<?php

namespace App\Http\Controllers;

use App\Entities\Sms\Services\SmsInterface;
use Illuminate\Http\Request;
use App\Repository\PostRepository as Post;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(SmsInterface $sms)
    {
        $sms->sendSms('15910470214', ['code'=>1234]);
//        return view('home');
    }
}
