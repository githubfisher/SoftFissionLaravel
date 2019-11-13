<?php
namespace App\Validators\Material;

use \Prettus\Validator\LaravelValidator;
use \Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class NewsDetailValidator.
 *
 * @package namespace App\Validators\Material;
 */
class NewsDetailValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'title'              => 'required|string|max:192',
            'thumb_url'          => 'required|url|max:255',
            'image_id'           => 'required|integer|min:1',
            'digest'             => 'sometimes|nullable|string|max:192',
            'author'             => 'sometimes|nullable|string|max:24',
            'content_source_url' => 'sometimes|nullable|url|max:255',
            'content'            => 'sometimes|nullable|string',
            'poster_id'          => 'sometimes|nullable|integer|min:1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'title'              => 'required|string|max:192',
            'thumb_url'          => 'required|url|max:255',
            'image_id'           => 'required|integer|min:1',
            'digest'             => 'sometimes|nullable|string|max:192',
            'author'             => 'sometimes|nullable|string|max:24',
            'content_source_url' => 'sometimes|nullable|url|max:255',
            'content'            => 'sometimes|nullable|string',
            'poster_id'          => 'sometimes|nullable|integer|min:1',
        ],
    ];
}
