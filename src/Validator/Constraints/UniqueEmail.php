<?php

// src/Validator/Constraints/UniqueEmail.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueEmail extends Constraint
{
    public $message = 'This email is already in use.';
}
