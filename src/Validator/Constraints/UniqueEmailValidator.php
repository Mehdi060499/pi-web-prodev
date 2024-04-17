<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\UserRepository;

class UniqueEmailValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        // Vérification si l'email existe déjà dans la base de données
        $existingUser = $this->userRepository->findOneByEmail($value);

        if ($existingUser) {
            // Ajouter une violation si l'email existe déjà
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}