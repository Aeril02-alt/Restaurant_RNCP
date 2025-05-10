<?php
// src/DataFixtures/PictureFixtures.php
namespace App\DataFixtures;

use App\DataFixtures\RestaurantFixtures;
use App\Entity\Restaurant;
use App\Entity\Picture;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            // on récupère une référence restaurant1…restaurant20
            $rand = random_int(1, 20);
            /** @var Restaurant $restaurant */
            $restaurant = $this->getReference('restaurant' . $rand, Restaurant::class);

            $picture = (new Picture())
                ->setTitre("Photo n°$i")
                // si vous avez un utilitaire Utils::slugify, sinon mettez un slug statique ou calculez-le vous-même
                ->setSlug(sprintf('photo-%d', $i))
                ->setRestaurant($restaurant)
                ->setCreatedAt(new DateTimeImmutable())
            ;

            $manager->persist($picture);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        // On veille à ce que RestaurantFixtures ait tourné avant
        return [
            RestaurantFixtures::class,
        ];
    }
}
