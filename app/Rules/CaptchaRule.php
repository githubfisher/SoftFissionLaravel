<?php

namespace App\Rules;

use Illuminatech\Validation\Composite\CompositeRule;

class CaptchaRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['string', 'min:4', 'max:6', 'alpha_num'];
    }

    public function message()
    {
        return '验证码格式错误';
    }
}
