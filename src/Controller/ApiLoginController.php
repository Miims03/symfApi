<?php

// src/Controller/ApiLoginController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): JsonResponse
    {https://127.0.0.1:8000/api/login
        // Vous pouvez récupérer les erreurs de connexion si nécessaire
        $error = $authenticationUtils->getLastAuthenticationError();
        
        if ($error) {
            return new JsonResponse([
                'message' => 'Erreur d\'authentification',
                'error' => $error->getMessageKey()
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Le succès est géré automatiquement par `json_login`, donc cette partie n'est pas nécessaire
        return new JsonResponse([
            'message' => 'Vous êtes authentifié',
            'token' => $this->generateToken($request->getUser()) // Fonction fictive pour générer un token, modifiez selon votre implémentation
        ]);
    }

    private function generateToken(string $username): string
    {
        // Exemple de génération de token. Cette fonction est fictive et doit être remplacée
        // par la logique réelle de génération de token JWT ou autre.
        return 'example-token-for-' . $username;
    }
}
