<?php
namespace App\Validators\Permission;

use App\Rules\GuardNameRule;
use App\Rules\PermissionNameRule;
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
            'name'       => ['required', new PermissionNameRule],
            'guard_name' => ['required', new GuardNameRule],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name'       => ['required', PermissionNameRule::class],
            'guard_name' => ['sometimes|required', GuardNameRule::class],
        ],
    ];
}
