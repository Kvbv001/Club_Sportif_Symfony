<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;


class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig' );
    }

    // la liste des catégories
    #[Route('/categories', name: 'app_categorie_get_all')]
    public function getAllLicencies(Connection $connection): Response
    {
        $sql = 'SELECT * FROM categorie';

        $donnees = $connection->fetchAllAssociative($sql);

        $formattedCategorie = [];

        foreach ($donnees as $data) {
            // Formatez les données comme vous le souhaitez
            $formattedCategorie[] = [
                'id' => $data['idCategorie'],
                'code' => $data['code'],
                'nom' => $data['nom'],
            ];
        }

        return $this->json(['success' => true, 'liste_categories' => $formattedCategorie]);
    }

}
