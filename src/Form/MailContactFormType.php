<?php

namespace App\Form;

use App\Entity\MailContact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('emailContact',EmailType::class, [
                'label' => 'À',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Example@email.com'],
                'mapped' => false
            ])
            ->add('objet', TextType::class, [
                'attr' => ['class' => 'form-control'],
                
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['id' => 'simplemde1', 'rows' => 6, 'style' => 'width: 100%;'],
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MailContact::class,
        ]);
    }
}
