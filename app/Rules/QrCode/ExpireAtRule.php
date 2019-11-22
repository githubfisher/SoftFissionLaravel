<?php

namespace App\Rules\QrCode;

use Illuminatech\Validation\Composite\CompositeRule;

class ExpireAtRule extends CompositeRule
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
