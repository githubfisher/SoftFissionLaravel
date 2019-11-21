<?php
namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class AppIdRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'min:18', 'alpha_dash'];
    }

    public function message()
    {
        return '格式错误';
    }
}
