<?php
namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class RepliesRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['array', 'min:1'];
    }

    public function message()
    {
        return '格式错误';
    }
}
