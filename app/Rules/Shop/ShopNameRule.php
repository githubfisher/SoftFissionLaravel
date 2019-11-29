<?php
namespace App\Rules\Shop;

use Illuminatech\Validation\Composite\CompositeRule;

class ShopNameRule extends CompositeRule
{
    protected function rules(): array
    {
        return ['required', 'string', 'alpha_dash', 'max:64'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
