<?php
namespace App\Validators\Material;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class NewsValidatorValidator.
 *
 * @package namespace App\Validators\Material;
 */
class NewsValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'app_id'                       => 'required|string|min:18',
            'details'                      => 'required|array|min:1',
            'details.*.title'              => 'required|string|max:192',
            'details.*.thumb_url'          => 'required|url|max:255',
            'details.*.image_id'           => 'required|integer|min:1',
            'details.*.digest'             => 'sometimes|nullable|string|max:192',
            'details.*.author'             => 'sometimes|nullable|string|max:24',
            'details.*.content_source_url' => 'sometimes|nullable|url|max:255',
            'details.*.content'            => 'sometimes|nullable|string',
            'details.*.poster_id'          => 'sometimes|nullable|integer|min:1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'app_id'                       => 'required|string|min:18',
            'details'                      => 'required|array|min:1',
            'details.*.title'              => 'required|string|max:192',
            'details.*.thumb_url'          => 'required|url|max:255',
            'details.*.image_id'           => 'required|integer|min:1',
            'details.*.digest'             => 'sometimes|nullable|string|max:192',
            'details.*.author'             => 'sometimes|nullable|string|max:24',
            'details.*.content_source_url' => 'sometimes|nullable|url|max:255',
            'details.*.content'            => 'sometimes|nullable|string',
            'details.*.poster_id'          => 'sometimes|nullable|integer|min:1',
        ],
    ];
}
