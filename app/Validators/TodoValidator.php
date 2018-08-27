<?php

namespace App\Validators;

use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

/**
 * Class TodoValidator.
 *
 * @package namespace App\Validators;
 */
class TodoValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'task' => 'required',
            'priority' => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'task' => 'required',
            'priority' => 'required',
        ],
    ];
}
