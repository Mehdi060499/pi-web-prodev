<?php

namespace App\Form;

use App\Entity\Users;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, [
                'label' => 'User ID',
                'attr' => [
                    'placeholder' => 'Enter User ID',
                ],
            ])
            ->add('delete', SubmitType::class, [
                'label' => 'DELETE',
                'attr' => ['class' => 'btn btn-danger'],
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
