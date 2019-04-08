<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\GoodsSku;
use App\Models\Address;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_type'=>['required','integer'],
            'address_id' => ['required','integer',
            Rule::in($this->user()->addresses->pluck('id')->all()),
           // Rule::exists('addresses', 'id')->where('user_id', $this->user()->id),
        ],
            'order_items.*.goods_sku_id'=>[
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = GoodsSku::find($value)) {
                        return $fail('该商品不存在');
                    }
                    if (!$sku->goods->is_on_sale) {
                        return $fail('该商品未上架');
                    }
                    if ($sku->stock === 0) {
                        return $fail('该商品已售完');
                    }
                    // 获取当前索引
                    preg_match('/goods\.(\d+)\.sku_id/', $attribute, $m);
                    $index = $m[1];
                    // 根据索引找到用户所提交的购买数量
                    $amount = $this->input('goods')[$index]['amount'];
                    if ($amount > 0 && $amount > $sku->stock) {
                        return $fail('该商品库存不足');
                    }
                },
        ],
        'order_items.*.amount'=>['required', 'integer', 'min:1']
    ];
    }
}
