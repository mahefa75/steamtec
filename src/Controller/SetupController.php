<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetupController extends AbstractController
{
    #[Route('/setup/create-users', name: 'app_setup_create_users')]
    public function createUsers(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Création de l'admin
        $admin = new User();
        $admin->setEmail('admin@steamtec.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $passwordHasher->hashPassword($admin, 'admin123!')
        );

        $entityManager->persist($admin);

        // Création du client
        $client = new User();
        $client->setEmail('client@steamtec.fr');
        $client->setRoles(['ROLE_USER']);
        $client->setPassword(
            $passwordHasher->hashPassword($client, 'client123!')
        );

        $entityManager->persist($client);
        $entityManager->flush();

        return new Response(
            '<html><body>
                <h1>Utilisateurs créés avec succès :</h1>
                <ul>
                    <li>Admin - admin@steamtec.fr (mot de passe: admin123!)</li>
                    <li>Client - client@steamtec.fr (mot de passe: client123!)</li>
                </ul>
                <p><a href="/login">Aller à la page de connexion</a></p>
            </body></html>'
        );
    }
} 