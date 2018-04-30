<?php
/**
 * Created by PhpStorm.
 * User: macej
 * Date: 28/12/2017
 * Time: 15:36
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label'=> 'form.email'))
            ->add('lastname', TextType::class, array('label' => 'form.lastname'))
            ->add('firstname' ,TextType::class, array('label' => 'form.firstname', 'required'=> false))
            ->add('address', TextType::class)
            ->add('zipCode', IntegerType::class)
            ->add('city', TextType::class)
            ->add('phone' , TelType::class)
            ->add('birth', BirthdayType::class, array('format'=> 'dd-MM-yyyy'))
            ->add('newsletter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}