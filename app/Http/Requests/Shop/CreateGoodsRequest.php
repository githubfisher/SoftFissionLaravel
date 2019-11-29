<?php
namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class CreateGoodsRequest extends FormRequest
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
            'name'            => ['required', 'string'],
            'shop_id'         => ['required', 'integer'],
            'recommend_price' => ['required', 'integer'],
            'price'           => ['required', 'integer'],
            'cost'            => ['sometimes', 'integer'],
            'introduction'    => ['sometimes', 'string'],
            'details'         => ['sometimes', 'string'],
            'type'            => ['required', 'integer'],
            'verificate_type' => ['required', 'integer'],
            'delivery_type'   => ['required', 'integer'],
            'pay_type'        => ['required', 'integer'],
            'status'          => ['sometimes', 'integer'],
            'stock'           => ['sometimes', 'integer'],
            'sold'            => ['sometimes', 'integer'],
            'expire_start'    => ['sometimes', 'date'],
            'expire_end'      => ['sometimes', 'date'],
            'banners'         => ['required', 'array', 'min:1'],
            'promotions'      => ['sometimes', 'array', 'min:1'],
        ];
    }
}
