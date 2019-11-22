<?php

namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class MaterialIdRule extends CompositeRule
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
