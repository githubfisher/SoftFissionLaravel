<?php
namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class LimitRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['integer', 'min:10'];
    }

    public function message()
    {
        return '每页条数格式错误';
    }
}
