<?php


namespace App\Form;


use App\Entity\Item;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Flex\Options;

class UsersControllerForm extends AbstractType
{
//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//        $builder
//            ->add('email')
//            ->add('role', ChoiceType::class, [
//                'choices'  => [
//                    'Admin' => null,
//                    'User' => true,
//            ]])
//            ->add('save', SubmitType::class)
//        ;
//    }



}