<?php
// src/DataFixtures/RestaurantFixtures.php
namespace App\DataFixtures;

use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        // On veut qu'UserFixtures soit exécuté AVANT pour pouvoir
        // faire $this->getReference('user1') si besoin
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $r = (new Restaurant())
                ->setName("Restaurant n°$i")
                ->setDescription("Desc $i")
                ->setMaxGuest(random_int(10, 50))
                ->setCreatedAt(new DateTimeImmutable());
            $manager->persist($r);
            $this->addReference('restaurant'.$i, $r);
        }
        $manager->flush();
    }
}
