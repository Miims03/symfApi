<?php
// src/Controller/ApiRegistrationController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiRegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validate input
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $username = $data['username'] ?? null;
        $lastname = $data['lastname'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Email and password are required.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $password
            )
        );
        $user->setRoles(['ROLE_USER']);
        $user->setUsername($username);
        $user->setLastname($lastname);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        // Validate user entity
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Save user to the database
        try {
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse(['message' => 'User registered successfully.'], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            // Handle the exception
            return new JsonResponse(['error' => 'An error occurred while registering the user.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
