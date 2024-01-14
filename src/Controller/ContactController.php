<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        
        return $this->render('contact/index.html.twig',);
    }

    #[Route('/contacts', name: 'app_contact_getAll')]
    public function getAllLicences(Connection $connection): Response
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
    
        return $this->json(['success' => true, 'liste_contacts' => $formattedLicences]);
    }
}
