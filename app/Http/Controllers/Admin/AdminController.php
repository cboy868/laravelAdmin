<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/22
 * Time: 14:03
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;

class AdminController extends ApiController
{

    //按功能 而不是按前后台制作接口 这个有明确界限的可以，没有的话直接放大杂烩就ok

    //get接口可添加一些参数，用以确定调用内容条数、是否与某些表关联、关联表取哪些字段等

    //如limit=10,出现此参数可考虑不用分页数据了，page_size出现此参数是要分页数据的
    //with=[user=>[name, email]] 关联表user 并取字段name,email
}