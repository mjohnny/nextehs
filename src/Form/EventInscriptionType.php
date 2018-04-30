<?php

namespace App\Form;

use App\Entity\EventInscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventInscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('lastname')
          ->add('firstname')
          ->add('phone', TelType::class)
          ->add('email', EmailType::class)
          ->add('mobility', CheckboxType::class, ['label' => 'Mobility_full', 'required' => false])
          ->add('addInfo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventInscription::class,
        ]);
    }
}
