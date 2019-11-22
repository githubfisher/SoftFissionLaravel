<?php
namespace App\Rules\Reply;

use Illuminatech\Validation\Composite\CompositeRule;

class StartAtRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['nullable', 'date'];
    }

    public function message()
    {
        return '格式错误';
    }
}
