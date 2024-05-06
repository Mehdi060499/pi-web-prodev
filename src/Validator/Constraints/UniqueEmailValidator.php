<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\UserRepository;
use App\Repository\VendeurRepository;
class UniqueEmailValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;



class UniqueEmailValidatorv extends ConstraintValidator
{
    private $vendeurRepository;

    public function __construct(VendeurRepository $vendeurRepository)
    {
        $this->vendeurRepository = $vendeurRepository;

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
        public function validatev($value, Constraint $constraint)
    {
        // Vérification si l'email existe déjà dans la base de données

        $existingUser = $this->vendeurRepository->findOneByEmail($value);


        if ($existingUser) {
            // Ajouter une violation si l'email existe déjà
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}