<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\PostRepository as Post;
use Intervention\Image\ImageManager;

class HomeController extends Controller
{
    public $manager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageManager $manager)
    {
        $this->manager = $manager;

//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        $file = storage_path('app/public/pictures/tmp/2.png');

        $img = $this->manager->make($file);

//        $img->blur();		// 轻微的高斯模糊
        $img->blur(30);
        $img->save(storage_path('app/public/pictures/tmp/2blur.png'));

//        return view('home');
    }
}
