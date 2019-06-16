<?php

namespace App\Http\Controllers\Admin\V1\Product;

use App\Entities\Product\Repository\ProductRepository;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;

class ProductController extends AdminController
{

    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', self::DEFAULT_PAGE_SIZE);

        //查询条件
        $where = [];
        if ($store_id = $request->input('store_id')) {
            array_push($where, ['store_id'=>$store_id]);
        }

        $result = $this->productRepository->where($where)
            ->with(['store'=>function($query){
                $query->select('id','name');
            }])
            ->with(['category'=>function($query){
                $query->select('id','name');
            }])
            ->orderBy('id', 'DESC')
            ->paginate($pageSize);

        return $this->respond($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
