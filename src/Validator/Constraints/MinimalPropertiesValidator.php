<?php
// src/Validator/Constraints/MinimalPropertiesValidator.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class MinimalPropertiesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        // Verifica si el valor es un array y si le faltan las claves 'description' o 'price'
        if (!is_array($value) || array_diff(['description', 'price'], array_keys($value))) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
