<?php

namespace App\Http\Controllers;
use App\Common\ApiStatus;
use App\Entities\Focus\Repository\FocusRepository;
use App\Entities\Goods\Repository\GoodsRepository;
use App\Entities\Pictures\Repository\PicturesRepository;

/**
 * 集合页面
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends ApiController
{
    public $pictures;
    public $focus;
    public $goods;

    public function __construct(PicturesRepository $picturesRepository,
                                FocusRepository $focusRepository,
                                GoodsRepository $goodsRepository)
    {
        $this->pictures = $picturesRepository;
        $this->focus = $focusRepository;
        $this->goods = $goodsRepository;
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
        $top_banners = $this->focus->where(['pos'=>'home_top'])->with('items')->first();
        $midle_banners = $this->focus->where(['pos'=>'home_middle'])->with('items')->first();

        $hots = $this->pictures->where([['flag', 1]])
            ->with('cover')
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $recommends = $this->pictures->where([['flag', 1]])
            ->with('cover')
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';

        return $this->respond([
            'base_url' =>  $baseUrl,
            'top_banners' => $top_banners,
            'middle_banners' => $midle_banners,
            'hot_meitu' => $recommends,
            'hot_manhua' => $hots,
        ]);
    }

    /**
     * 发现 最新的一些内容吗
     */
    public function discover()
    {

        $pageSize = 20;

        $discover = $this->pictures->where([['flag', 1]])
            ->with('cover')
            ->whereHas('category', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'desc')
            ->paginate($pageSize);


        if ($discover) {
            $arr = $discover->toArray();
        } else {
            return $this->failed(ApiStatus::CODE_1021);
        }

        $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';

        $arr['base_url'] = $baseUrl;

        return $this->respond($arr);
    }

    /**
     * 商品
     */
    public function goods()
    {
        $where =[['cid'=>1]];

        $result = $this->goods
            ->where($where)
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();

        return $this->respond($result);
    }

    /**
     * 我的 个人中心
     * 可修改个人的一些东西
     */
    public function me()
    {
        
    }
}
