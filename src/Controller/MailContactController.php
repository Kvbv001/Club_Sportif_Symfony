<?php

namespace App\Controller;

use App\Form\MailContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use App\Entity\MailContact;
use Symfony\Component\Mime\Address;
use Doctrine\DBAL\Connection;

class MailContactController extends AbstractController
{
    #[Route('/mail/contact', name: 'app_mail_contact')]
    public function index(Request $request, EntityManagerInterface $manager, MailerInterface $mailer,Connection $connection): Response
    {

        //recuperer tous mes mails 

        $sql = 'SELECT
        mc.message ,
        mc.date_envoi,
        mc.id,
        mc.objet,
        c.nom,
        c.prenom        
     FROM mail_contact mc
      JOIN contact c ON c.idContact = mc.contact_id
';
$donnees = $connection->fetchAllAssociative($sql);

$formattedEmailContact = [];

foreach ($donnees as $data) {
 // Formatez les données comme vous le souhaitez
 $formattedEmailContact[] = [
     'message' => $data['message'],
     'date' => new \DateTime($data['date_envoi']), 
     'objet' => $data['objet'],
     'id' => $data['id'],
     'contact' => [
         'nom' => $data['nom'],
         'prenom' => $data['prenom'],
     ],
     // ... autres propriétés
 ];
}
//


        $mailContact = new MailContact(); // Créez une nouvelle instance de l'entité MailContact
        $form = $this->createForm(MailContactFormType::class, $mailContact);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mailContact->setDateEnvoi(new \DateTimeImmutable());
                $emailContact= $form->get('emailContact')->getData();
                $query = "SELECT idContact FROM contact WHERE email = :email";
                $statement = $manager->getConnection()->executeQuery($query, ['email' => $emailContact]);
                $result = $statement->fetchFirstColumn();
                 if ($result) {
                $idContact = $result[0];
                $mailContact->setContactId($idContact);
                $mailContact->addContact($idContact);
                // Définissez les autres propriétés en fonction des données du formulaire
                $manager->persist($mailContact);
                $manager->flush();

                $email = (new Email())
                    ->from(new Address('kboumar17@gmail.com'))
                    ->to( new Address($emailContact))
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject($mailContact->getObjet())
                    ->text(body:$mailContact->getMessage())
               // ->html('<p>See Twig integration for better HTML integration!</p>');
               ;
                $mailer->send($email);
                $this->addFlash(
                    'success','Email Envoyé avec succès !'
                );
                return $this->redirectToRoute('app_mail_contact');
        } else {
            $this->addFlash(
                'error', "Ce email n'existe pas dans les emails des contacts."
            );
            return $this->render('contact/email.html.twig', [
                'form' => $form->createView(),'emails'=>$formattedEmailContact
            ]);
        }
       
        }

      
        return $this->render('contact/email.html.twig', [
            'form' => $form->createView(),'emails'=>$formattedEmailContact
        ]);
    }


    #[Route('/contact/email/read/{id}', name: 'app_contact_email_read')]
    public function readEmail($id, EntityManagerInterface $entityManager, Connection $connection): Response
    {
        $contact = $entityManager->getRepository(MailContact::class)->find($id);
        $sql = 'SELECT * FROM contact where idContact =:idContact';

        $donnees = $connection->fetchAllAssociative($sql, ['idContact' => $contact->getContactId()]);
        return $this->render('contact/read_email.html.twig', ['contact_email' => $contact, 'donnees' => $donnees[0]]);
    }


    #[Route('/contact/email/delete/{id}', name: 'app_contact_email_delete')]
    public function deleteEmail($id, EntityManagerInterface $entityManager)
    {
        // Charger l'entité à partir de la base de données
        $email = $entityManager->getRepository(MailContact::class)->find($id);
        if (!$email) {
            // Gérer le cas où l'entité n'est pas trouvée
            $this->addFlash('errors', 'Aucune email retrouvé');
            return $this->redirectToRoute('app_mail_edu');
        }

        // Supprimer l'entité
        $entityManager->remove($email);
        $entityManager->flush();

        // Ajouter un message flash pour confirmer la suppression (facultatif)
        $this->addFlash('success', 'Email supprimé avec succès');

        // Rediriger vers une autre page (par exemple, la liste des emails)
        return $this->redirectToRoute('app_mail_edu');
    }

}