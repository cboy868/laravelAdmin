<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\PostRepository as Post;

class HomeController extends Controller
{


    private $post;

    public function __construct(Post $post) {
        $this->post = $post;
    }

    public function index() {


        return \Response::json($this->post->all());
    }






    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }
//
//    /**
//     * Show the application dashboard.
//     *
//     * @return \Illuminate\Contracts\Support\Renderable
//     */
//    public function index()
//    {
//
//
//        return view('home');
//    }
}
