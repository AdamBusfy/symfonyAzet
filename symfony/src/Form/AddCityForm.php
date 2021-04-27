<?php


namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'City name',
            ])
            ->add('submitButton', SubmitType::class, [
                'label'=>'Create record',
                'attr'=> ['class' =>'btn btn-success btn-xs']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}