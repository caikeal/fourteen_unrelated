<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class ScanOrderRequest extends FormRequest
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
            'code'   => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0', 'not_in:0', 'max:100000000', 'regex:/^[0-9]+\.{0,1}[0-9]{1,2}$/'],
        ];
    }

    public function messages()
    {
        return [
            'code.required'     => '缺少用户码',
            'code.string'       => '未知用户',
            'amount.required'   => '金额必填',
            'amount.numeric'    => '金额类型不对',
            'amount.min'        => '金额必须大于0',
            'amount.not_in'     => '金额必须大于0',
            'amount.max'        => '金额超过在线支付允许上限',
            'amount.regex'      => '金额超过格式错误',
        ];
    }
}
