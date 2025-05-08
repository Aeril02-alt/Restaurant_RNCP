<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use DateTimeImmutable;

#[route('/restaurant', name: 'app_api_restaurant_')]
/**
 * RestaurantController
 *
 * @package App\Controller
 */
class RestaurantController extends AbstractController
{
    private RestaurantRepository $repository;
    private EntityManagerInterface $manager;
    private SerializerInterface $serializer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        RestaurantRepository $repository,
        EntityManagerInterface $manager,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    )
     {
        $this->repository   = $repository;
        $this->manager      = $manager;
        $this->serializer   = $serializer;
        $this->urlGenerator = $urlGenerator;
    }


    #[Route('/', name: 'index', methods:'POST')]
    public function new(Request $request): JsonResponse
    {
        $restaurant = $this->serializer->deserialize(
            $request->getContent(),
            Restaurant::class,
            'json'
        );
        $restaurant->setCreatedAt(new DateTimeImmutable());
        $this->manager->persist($restaurant);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($restaurant, 'json');
        $location     = $this->urlGenerator->generate(
            'app_api_restaurant_show',
            ['id' => $restaurant->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse(
            $responseData,
            Response::HTTP_CREATED,
            ['Location' => $location],
            true
        );
    }
    #[Route('/{id}/show', name: 'show', methods:'GET')]
    public function show(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if (!$restaurant) {
            $responseData = $this->serializer->serialize($restaurant, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
    #[Route('/{id}/edit', name: 'edit', methods:'PUT')]
    public function edit(int $id, Request $request): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for {$id} id");
        }

        $this->serializer->deserialize(
            $request->getContent(),
            Restaurant::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant]
        );

        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($restaurant, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
    #[Route('/{id}/delete', name: 'delete', methods:'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if (!$restaurant) {
            throw $this->createNotFoundException("No Restaurant found for {$id} id");
        }
        $this->manager->remove($restaurant);
        $this->manager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

