<?php
namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class SceneRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'in:register,login,reset,auth'];
    }

    public function message()
    {
        return '场景值格式错误';
    }
}
