<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $usersData = [
            [
                'email' => 'admin@admin.com',
                'password' => 'admin',
                'roles' => ['ROLE_ADMIN']
            ],
            [
                'email' => 'user1@user.com',
                'password' => 'user1',
                'roles' => ['ROLE_USER']
            ],
            [
                'email' => 'user2@user.com',
                'password' => 'user2',
                'roles' => ['ROLE_USER']
            ]
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
