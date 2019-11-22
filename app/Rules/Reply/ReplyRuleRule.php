<?php

namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class ReplyRuleRule extends CompositeRule
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
