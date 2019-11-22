<?php

namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class KeywordsRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['array'];
    }

    public function message()
    {
        return '格式错误';
    }
}
