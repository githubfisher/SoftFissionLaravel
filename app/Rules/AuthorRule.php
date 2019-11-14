<?php

namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class AuthorRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'max:64', 'alpha_dash'];
    }

    public function message()
    {
        return '作者名称格式错误';
    }
}
