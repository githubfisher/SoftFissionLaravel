<?php

namespace App\Rules\QrCode;

use Illuminatech\Validation\Composite\CompositeRule;

class ExpireTypeRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['integer', 'in:1,2'];
    }

    public function message()
    {
        return '格式错误';
    }
}
