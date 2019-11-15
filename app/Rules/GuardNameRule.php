<?php

namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class GuardNameRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'max:64', 'alpha_num'];
    }

    public function message()
    {
        return '格式错误';
    }
}
