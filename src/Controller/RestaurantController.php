<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;

#[route('/restaurant', name: 'app_api_restaurant')]
/**
 * RestaurantController
 *
 * @package App\Controller
 */
class RestaurantController extends AbstractController
{
    private RestaurantRepository $repository;
    private EntityManagerInterface $manager;

    public function __construct(RestaurantRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    #[Route('/', name: 'index', methods:'POST')]
    public function new(): JsonResponse
    {
            $restaurant = new Restaurant();
            $restaurant->setName('Restaurant Name');
            $restaurant->setDescription('Restaurant Description');
            $restaurant->setUpdatedAt(new \DateTimeImmutable());

            return $this->json(
                ['message' =>"restaurant {$restaurant->getName()} created"],
                status: Response::HTTP_CREATED,
            );
    }
    #[Route('/{id}/show', name: 'show', methods:'GET')]
    public function show(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for {$id} id");
        }

        return $this->json(
            ['message' => "A Restaurant was found : {$restaurant->getName()} for {$restaurant->getId()} id"]
        );
    }
    #[Route('/{id}/edit', name: 'edit', methods:'PUT')]
    public function edit(int $id): Response
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for {$id} id");
        }

        $restaurant->setName('Restaurant name updated');
        $this->manager->flush();

        return $this->redirectToRoute('app_api_restaurant_show', ['id' => $restaurant->getId()]);
    }
    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     */
    
    #[Route('/{id}/delete', name: 'delete', methods:'DELETE')] 
    public function delete(int $id): Response
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for {$id} id");
        }

        $this->manager->remove($restaurant);
        $this->manager->flush();

        return $this->json(['message' => "Restaurant resource deleted"], Response::HTTP_NO_CONTENT);
    }
}