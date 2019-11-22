<?php

namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class ReplyTypeRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['integer', 'in:1,2,3,4,5,6'];
    }

    public function message()
    {
        return '格式错误';
    }
}
