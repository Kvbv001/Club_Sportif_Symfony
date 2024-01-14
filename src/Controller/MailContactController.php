<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailContactController extends AbstractController
{
    #[Route('/mail/contact', name: 'app_mail_contact')]
    public function index(): Response
    {
        return $this->render('contact/email.html.twig', [
            'controller_name' => 'MailContactController',
        ]);
    }
}
