<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Common\ApiStatus;
use App\Entities\Order\Helper;
use App\Entities\Pay\Repository\PayRepository;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Validation\UnauthorizedException;

class PayDecorator extends DecoratorAbs
{

    protected $payRepository;

    public $local_trade_no;

    public $total_fee = 0;


    public function __construct(ComponentInterface $componnet, PayRepository $payRepository)
    {

        parent::__construct($componnet);

        $this->payRepository = $payRepository;
        $this->local_trade_no = Helper::createPayNo();
    }

    public function filter(): bool
    {
        if (!$this->component->filter()) {
            return false;
        }
        return true;
    }

    public function getData(): array
    {
        $data = $this->component->getData();

        $data['pay'] = [
            'local_trade_no' => $this->local_trade_no,
            'total_fee' => $this->total_fee
        ];

        return $data;
    }

    public function create(): bool
    {
        if (!$this->component->create()) {
            return false;
        }

        $data = $this->getData();

        $this->total_fee = $data['order']['price'];

        $dbData = [
            'title' => $data['order']['title'],
            'user_id' => $data['user']['id'],
            'order_id' => $data['order']['order_id'],
            'order_no' => $data['order']['order_no'],
            'local_trade_no' => $this->local_trade_no,
            'total_fee' => $this->total_fee,
        ];

        if (!$this->payRepository->create($dbData)) {
            throw new \Exception('数据添加失败', ApiStatus::CODE_4002);
        }

        return true;
    }
}