<?php

namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class UsernameRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'max:64', 'alpha_dash'];
    }

    public function message()
    {
        return '用户名格式错误';
    }
}
