<?php
namespace App\Validators\Permission;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class PermissionValidator.
 *
 * @package namespace App\Validators\Permission;
 */
class PermissionValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name'       => ['required', 'string', 'max:64', 'alpha_dash'],
            'guard_name' => ['required', 'string', 'max:64', 'alpha_num'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name'       => ['required', 'string', 'max:64', 'alpha_dash'],
            'guard_name' => ['sometimes|required', 'string', 'max:64', 'alpha_num'],
        ],
    ];
}
