<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

class LicencieController extends AbstractController
{
    #[Route('/licence', name: 'app_licencie')]
    public function index(): Response
    {
        return $this->render('licencie/index.html.twig');
    }



    #[Route('/licencies', name: 'app_licencies_getAll')]
    public function getAllLicencies(Connection $connection): Response
    {
        $sql = 'SELECT
                    l.numeroLicence AS licence_id,
                    l.nom AS licence_nom,
                    l.prenom AS licence_prenom,
                    c.idContact AS contact_id,
                    c.nom AS contact_nom,
                    c.prenom AS contact_prenom,
                    c.email AS contact_email,
                    c.telephone AS contact_telephone,
                    cat.idCategorie AS categorie_id,
                    cat.nom AS categorie_nom
                FROM licence l
                JOIN contact c ON l.idContact = c.idContact
                JOIN categorie cat ON l.idCategorie = cat.idCategorie';

        $donnees = $connection->fetchAllAssociative($sql);

        $formattedLicences = [];

        foreach ($donnees as $data) {
            // Formatez les données comme vous le souhaitez
            $formattedLicences[] = [
                'numeroLicence' => $data['licence_id'],
                'nom' => $data['licence_nom'],
                'prenom' => $data['licence_prenom'],
                'categorie' => [
                    'idCategorie' => $data['categorie_id'],
                    'categorie_nom' => $data['categorie_nom'],
                ],
                'contact' => [
                    'idContact' => $data['contact_id'],
                    'contact_nom' => $data['contact_nom'],
                    'contact_prenom' => $data['contact_prenom'],
                    'contact_email' => $data['contact_email'],
                    'contact_telephone' => $data['contact_telephone'],
                ],
                // ... autres propriétés
            ];
        }

        return $this->json(['success' => true, 'liste_licencies' => $formattedLicences]);
    }


    #[Route('/licences/{numeroLicence}', name: 'app_licences_getById', methods: ['GET'])]
    public function getById(Connection $connection, Request $request): Response
    {
        $numeroLicence = $request->attributes->get('numeroLicence');

        $sql = 'SELECT
                l.numeroLicence AS licence_id,
                l.nom AS licence_nom,
                l.prenom AS licence_prenom,
                c.idContact AS contact_id,
                c.nom AS contact_nom,
                c.prenom AS contact_prenom,
                c.email AS contact_email,
                c.telephone AS contact_telephone,
                cat.idCategorie AS categorie_id,
                cat.nom AS categorie_nom
            FROM licence l
            JOIN contact c ON l.idContact = c.idContact
            JOIN categorie cat ON l.idCategorie = cat.idCategorie
            WHERE l.numeroLicence = :numeroLicence';

        $donnees = $connection->fetchAllAssociative($sql, ['numeroLicence' => $numeroLicence]);

        if (empty($donnees)) {
            return $this->json(['success' => false, 'message' => 'Licence non trouvée'], 404);
        }

        $data = $donnees[0];

        // Formatez les données comme vous le souhaitez
        $formattedLicence = [
            'numeroLicence' => $data['licence_id'],
            'nom' => $data['licence_nom'],
            'prenom' => $data['licence_prenom'],
            'categorie' => [
                'idCategorie' => $data['categorie_id'],
                'categorie_nom' => $data['categorie_nom'],
            ],
            'contact' => [
                'idContact' => $data['contact_id'],
                'contact_nom' => $data['contact_nom'],
                'contact_prenom' => $data['contact_prenom'],
                'contact_email' => $data['contact_email'],
                'contact_telephone' => $data['contact_telephone'],
            ],
            // ... autres propriétés
        ];

        return $this->json(['success' => true, 'licence' => $formattedLicence]);
    }




}
