<?php

namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class DifferenceRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['integer', 'in:0,1'];
    }

    public function message()
    {
        return '格式错误';
    }
}
