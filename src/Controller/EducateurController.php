<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;


class EducateurController extends AbstractController
{
    #[Route('/educateur', name: 'app_educateur')]
    public function index(): Response
    {
        return $this->render('educateur/index.html.twig');
    }

    #[Route('/allEducateur', name: 'app_educateur_getAll')]
    public function getAllEducateur(Connection $connection): Response
    {
        $sql = 'SELECT * FROM educateur';

        $donnees = $connection->fetchAllAssociative($sql);

        $formattedLicences = [];

        foreach ($donnees as $data) {
            // Formatez les données comme vous le souhaitez
            $formattedEducateurs[] = [
                'numero' => $data['numero'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
            ];
        }

        return $this->json(['success' => true, 'liste_educateur' => $formattedEducateurs]);
    }

   

    #[Route('/educateur/email/read', name: 'app_educateur_email_read')]
    public function readEmail(): Response
    {
        return $this->render('educateur/read_email.html.twig');
    }
}
