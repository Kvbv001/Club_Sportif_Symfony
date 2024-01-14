<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;


class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(Connection $connection): Response
    {

        $sqlContact ='SELECT * FROM contact';
        $sqlEducateur = 'SELECT * FROM educateur';
        $sqlLicencie = 'SELECT * FROM licence';
        $sqlCategorie = 'SELECT * FROM categorie'; 

        $contacts = $connection->fetchAllAssociative($sqlContact);
        $educateurs = $connection->fetchAllAssociative($sqlEducateur);
        $licencies = $connection->fetchAllAssociative($sqlLicencie);
        $categories = $connection->fetchAllAssociative($sqlCategorie);
        return $this->render('dashboard/index.html.twig', [
            'contacts' => $contacts,
            'educateurs' => $educateurs,
            'licencies' => $licencies,
            'categories' => $categories,        ]);
    }
}

