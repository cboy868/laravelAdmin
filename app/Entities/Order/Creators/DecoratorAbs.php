<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Common\ApiStatus;
use App\Entities\Goods\Repository\GoodsRepository;
use App\Entities\Order\Repository\OrderGoodsRepository;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

abstract class DecoratorAbs implements ComponentInterface
{
    protected $component;

    public function __construct(ComponentInterface $component)
    {
        $this->component = $component;
    }

    public function filter(): bool
    {
        return true;
    }

    public function getData(): array
    {

        return [];
    }

    public function create(): bool
    {
        return true;
    }
}