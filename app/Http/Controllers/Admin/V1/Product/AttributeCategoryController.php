<?php

namespace App\Http\Controllers\Admin\V1\Product;

use App\Entities\Product\Repository\AttributeCategoryRepository;
use App\Entities\Product\Request\AttributeCategoryRequest;
use App\Http\Controllers\Admin\AdminController;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeCategoryController extends AdminController
{

    protected $attributeCategoryRepository;

    public function __construct(AttributeCategoryRepository $attributeCategoryRepository)
    {
        $this->attributeCategoryRepository = $attributeCategoryRepository;

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

        $where = [];

        $list = $this->attributeCategoryRepository->where(array_merge($where, ['pid' => 0]))
            ->orderBy('id', 'ASC')
            ->paginate($pageSize);

        if (!$list) {
            return $this->respond();
        }


        $codes = [];

        foreach ($list as $item) {
            array_push($codes, $item->code);
        }
        $codeStr = implode('', $codes);

        $result = DB::table('product_attribute_category')
            ->where('code', 'regexp', '[' . $codeStr . ']')
            ->get();

        $result = $result->toArray();

        $treeData = makeTree($result);

        return $this->respond($treeData);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeCategoryRequest $request)
    {
        $params = $request->only([
            'pid',
            'name',
            'intro'
        ]);


        try {
            $model = $this->attributeCategoryRepository->create($params);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond($model->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeCategoryRequest $request, $id)
    {
        $params = $request->only([
            'name',
            'intro'
        ]);

        try {
            $this->attributeCategoryRepository->update($params, $id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
