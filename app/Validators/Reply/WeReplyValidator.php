<?php
namespace App\Validators\Reply;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WeReplyValidator.
 *
 * @package namespace App\Validators\Reply;
 */
class WeReplyValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'difference'         => ['required', 'integer', 'in:1,2'],
            'reply_type'         => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'reply_type_female'  => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'content'            => ['sometimes', 'nullable', 'string', 'max:2048'],
            'content_female'     => ['sometimes', 'nullable', 'string', 'max:2048'],
            'material_id'        => ['sometimes', 'nullable', 'integer', 'min:1'],
            'material_id_female' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'difference'         => ['sometimes', 'required', 'integer', 'in:1,2'],
            'reply_type'         => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'reply_type_female'  => ['sometimes', 'nullable', 'integer', 'in:1,2,3,4,5,6'],
            'content'            => ['sometimes', 'nullable', 'string', 'max:2048'],
            'content_female'     => ['sometimes', 'nullable', 'string', 'max:2048'],
            'material_id'        => ['sometimes', 'nullable', 'integer', 'min:1'],
            'material_id_female' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ],
    ];
}
