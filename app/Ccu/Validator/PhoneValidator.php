<?php

namespace App\Ccu\Validator;

use Illuminate\Validation\Validator;

class PhoneValidator
{
    /**
     * Validate a given attribute against a rule.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, Validator $validator)
    {
        return preg_match('/^09\d{8}$/', $value);
    }
}
