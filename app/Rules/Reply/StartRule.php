<?php

namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class StartRule extends CompositeRule
{
    protected function rules(): array
    {
        return [];
    }

    public function message()
    {
        return '格式错误';
    }
}
