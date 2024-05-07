<?php

namespace App\Form;

use App\Entity\Vendeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File as AssertFile;
use Beelab\Recaptcha2Bundle\Validator\Constraints\Recaptcha2;
use Beelab\Recaptcha2Bundle\Form\Type\RecaptchaType;
class VendeurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('nomproduit')
            ->add('email')
            ->add('motdepasse')
            ->add('description')
            ->add('type')
            ->add('image', FileType::class, [
                'label' => 'Preuve visuelle',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new AssertFile([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPG, JPEG, PNG or GIF)',
                    ])
                ],
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vendeur::class,
        ]);
    }
}
