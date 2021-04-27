<?php


namespace App\Form;

use App\Entity\City;
use App\Entity\Record;
use App\Entity\User;
use App\Repository\CityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditRecordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['user'];

        $builder
            ->add('id', HiddenType::class, [
                'mapped' => false
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'query_builder' => function (CityRepository $repository) use ($user) {
                    return $repository->createQueryBuilder('qb')
                        ->select('c')
                        ->from(City::class, 'c')
                        ->andWhere('c.user = :user')
                        ->setParameter('user', $user);
                }
            ])
            ->add('temperature')
            ->add('humidity')
            ->add('pressure')
            ->add('submitButton', SubmitType::class, [
                'label' => 'Edit',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
            'user' => null,
        ]);
        $resolver->setAllowedTypes('user', User::class);
    }
}