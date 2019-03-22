<?php

namespace App\Entities\Focus\Repository;

use Cboy868\Repositories\Eloquent\Repository;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class FocusItemRepository extends Repository
{
    function model()
    {
        return 'App\Entities\Focus\FocusItem';
    }
}