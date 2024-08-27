<?php

// src/Controller/ApiUserController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    public function getUsers(EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer tous les utilisateurs depuis la base de données
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        // Transformer les utilisateurs en un tableau simple pour l'API
        $userArray = [];
        foreach ($users as $user) {
            $userArray[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'username' => $user->getUsername(),
                'lastname' => $user->getLastname(),
            ];
        }

        // Retourner les utilisateurs sous forme de réponse JSON
        return new JsonResponse($userArray, JsonResponse::HTTP_OK);
    }
}
