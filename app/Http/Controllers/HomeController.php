<?php

namespace App\Http\Controllers;
use App\Entities\Pictures\Repository\PicturesRepository;

/**
 * 集合页面
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends ApiController
{
    public $pictures;

    public function __construct(PicturesRepository $picturesRepository)
    {
        $this->pictures = $picturesRepository;
//        parent::__construct(); 不需要middleware
    }

    /**
     * 首页
     * 需要展示
     * 1、banner图
     * 2、pictures热、推等
     */
    public function index()
    {
        $hots = $this->pictures->where([['flag', 2]])
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();

        $recommends = $this->pictures->where([['flag', 1]])
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();

        return $this->respond([
            'recommends' => $recommends,
            'hots' => $hots
        ]);
    }

    /**
     * 发现 最新的一些内容吗
     */
    public function discover()
    {

    }

    /**
     * 我的 个人中心
     * 可修改个人的一些东西
     */
    public function me()
    {
        
    }
}
