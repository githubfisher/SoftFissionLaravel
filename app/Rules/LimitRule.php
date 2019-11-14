<?php

namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class LimitRule extends CompositeRule
{
    protected function rules(): array
    {
        return [];
    }

    public function message()
    {
        return '格式错误';
    }
}
