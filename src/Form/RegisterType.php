<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => false,'label' => 'Nachname', 'mapped' => false])
            ->add('pre_name', TextType::class,['required' => false,'label' => 'Vornamen', 'mapped' => false])
            ->add('adresse', TextType::class, ['required' => false,'label' => 'Starsse und Nummer', 'mapped' => false])
            ->add('plz', IntegerType::class, ['required' => false,'label' => 'Postleitzahl', 'mapped' => false])
            ->add('ort', TextType::class,['required' => false,'label' => 'Ort', 'mapped' => false])
            ->add('email', EmailType::class, ['required' => false,'label' => 'E-Mail Addresse', 'mapped' => false])
            ->add('tel', TelType::class, ['required' => false,'label' => 'Telefonummer', 'mapped' => false])
            ->add('startPunkt', HiddenType::class, ['mapped' => false])
            ->add('start_zeit', HiddenType::class, ['mapped' => false])
            ->add('uuid', HiddenType::class, ['mapped' => false])
        ;
    }
}
