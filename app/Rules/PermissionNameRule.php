<?php
namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class PermissionNameRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'max:64', 'alpha_dash'];
    }

    public function message()
    {
        return '格式错误';
    }
}
