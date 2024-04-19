<?php

namespace App\Form;

use App\Entity\Vendeur;
use App\Repository\VendeurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', TextType::class, [
            'label' => 'Email',
        ])
        ->add('motdepasse', PasswordType::class, [
            'label' => 'Password',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Sign In',
        ]);
    }
}
