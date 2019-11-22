<?php

namespace App\Rules\QrCode;

use Illuminatech\Validation\Composite\CompositeRule;

class ExpireInRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['integer', 'min:1'];
    }

    public function message()
    {
        return '格式错误';
    }
}
