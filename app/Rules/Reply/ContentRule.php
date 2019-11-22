<?php

namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class ContentRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'max:2048'];
    }

    public function message()
    {
        return '格式错误';
    }
}
