<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User; // Import the User entity
use Dom\Entity;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/', name: 'app_')] // This route is for the API endpoint
class SecurityController extends AbstractController
{
    /**
     * SecurityController
     *
     * @package App\Controller
     */
    public function __construct(
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    #[Route('/register', name: 'register', methods: ['POST'])]
    /**
     * Register a new user
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return JsonResponse
     */
    // This method handles user registration
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        // Check if the request is a POST request
        $user = $this->serializer->deserialize(
            $request->getContent(),
            User::class,
            'json'
        );

        // Check if the user already exists
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            )
        );
        
        // Set the API token and roles
        $user->setApiToken(bin2hex(random_bytes(32))); // Generate a random API token
        $user->setRoles(['ROLE_USER']); // Set default role to ROLE_USER
        // Set the created date

        // Persist the user entity
        // and flush to save it to the database
        $this->manager->persist($user);
        $this->manager->flush();

        // Return a JSON response with the user data
        return new JsonResponse([
            'user' => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()
        ],Response::HTTP_CREATED);
    }
#[Route('/login', name: 'login', methods: 'POST')]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user'  => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }
}
