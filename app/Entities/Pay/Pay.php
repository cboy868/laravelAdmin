<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 14:16
 */

namespace App\Entities\Pay;


use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    protected $table = 'order_pay';

    const RESULT_INIT = 1;//失败
    const RESULT_PROCESS = 2;//支付中
    const RESULT_SUCCESS = 3;//成功

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'order_id',
        'order_no',
        'title',
        'local_trade_no',
        'trade_no',
        'total_fee',
        'total_pay',
        'pay_method',
        'pay_result',
        'paid_at',
        'note',
        'status',
        'checkout_at',
        'created_at',
        'updated_at'
    ];


    public function success($total_pay)
    {
        $this->pay_result = self::RESULT_SUCCESS;
        $this->total_pay = $total_pay;
        $this->save();
    }

}