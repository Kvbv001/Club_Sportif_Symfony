<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

   

   



#[Route('/educateur/{numero}', name: 'app_educateurs_getById', methods: ['GET'])]
    public function getById(Connection $connection, Request $request): Response
    {
        $numero = $request->attributes->get('numero');

        $sql = 'SELECT
                e.numero AS educateur_id,
                e.nom AS educateur_nom,
                e.prenom AS educateur_prenom,
                e.email AS educateur_email,
                e.numero AS educateur_numero,
                c.nom AS contact_nom,
                c.prenom AS contact_prenom,
                c.email AS contact_email,
                c.telephone AS contact_telephone,
                cat.idCategorie AS categorie_id,
                cat.nom AS categorie_nom
            FROM educateur e
            JOIN licence l ON l.numeroLicence = e.numeroLicence
            JOIN categorie cat ON l.idCategorie = cat.idCategorie
            JOIN contact c ON l.idContact = c.idContact
            WHERE e.numero = :numero';

        $donnees = $connection->fetchAllAssociative($sql, ['numero' => $numero]);

        if (empty($donnees)) {
            return $this->json(['success' => false, 'message' => 'Educateur non trouvée'], 404);
        }

        $data = $donnees[0];

        // Formatez les données comme vous le souhaitez
        $formattedEducateur = [
            'numero' => $data['educateur_id'],
            'nom' => $data['educateur_nom'],
            'prenom' => $data['educateur_prenom'],
            'email' => $data['educateur_email'],
            'categorie' => [
                'idCategorie' => $data['categorie_id'],
                'categorie_nom' => $data['categorie_nom'],
            ],
            'contact' => [
                'contact_nom' => $data['contact_nom'],
                'contact_prenom' => $data['contact_prenom'],
                'contact_email' => $data['contact_email'],
                'contact_telephone' => $data['contact_telephone'],
            ],
            // ... autres propriétés
        ];

        return $this->json(['success' => true, 'educateur' => $formattedEducateur]);
    }
}