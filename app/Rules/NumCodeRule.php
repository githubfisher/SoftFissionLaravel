<?php

namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class NumCodeRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['integer', 'min:0', 'max:9999'];
    }

    public function message()
    {
        return '验证码格式错误';
    }
}
