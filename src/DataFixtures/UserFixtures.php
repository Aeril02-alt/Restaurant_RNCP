<?php
// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail("email.$i@gmail.fr")
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'.$i));
            $manager->persist($user);
            $this->addReference('user'.$i, $user);
        }
        $manager->flush();
    }
}
