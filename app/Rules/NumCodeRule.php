<?php

namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class NumCodeRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['integer', 'min:4', 'max:6'];
    }

    public function message()
    {
        return '验证码格式错误';
    }
}
