<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;



class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 2,
                    'max' => 255,
                ]),
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 10,
                    'max' => 1000,
                ]),
            ],
        ])
        ->add('prix', NumberType::class, [
            'label' => 'Prix',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Positive(),
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Image (JPEG, PNG, GIF)',
            'mapped' => false, // Le champ 'image' n'est pas lié à une propriété de l'entité
            'required' => true, // La validation se fait dans le contrôleur
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'maxSize' => '10240k',
                ]),
            ],
        ])
        ->add('quantite', IntegerType::class, [
            'label' => 'Quantité',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Positive(),
            ],
        ])
    ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
