<?php

namespace App\Controller;

use App\Form\MailEduFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use App\Entity\MailEdu;
use Symfony\Component\Mime\Address;
use Doctrine\DBAL\Connection;

class MailEduController extends AbstractController
{
    #[Route('/mail/edu', name: 'app_mail_edu')]
    public function index(Request $request, EntityManagerInterface $manager, MailerInterface $mailer,Connection $connection): Response
    {

        //recuperer tous mes mails 

        $sql = 'SELECT
        me.message ,
        me.date_envoi,
        me.objet,
        e.nom,
        e.prenom        
     FROM mail_edu me
      JOIN educateur e ON e.numero = me.id_educateur
';
$donnees = $connection->fetchAllAssociative($sql);

$formattedEmailEducateur = [];

foreach ($donnees as $data) {
 // Formatez les données comme vous le souhaitez
 $formattedEmailEducateur[] = [
     'message' => $data['message'],
     'date' => new \DateTime($data['date_envoi']), 
     'objet' => $data['objet'],
     'educateur' => [
         'nom' => $data['nom'],
         'prenom' => $data['prenom'],
     ],
     // ... autres propriétés
 ];
}
//


        $mailEdu = new MailEdu(); // Créez une nouvelle instance de l'entité MailEdu
        $form = $this->createForm(MailEduFormType::class, $mailEdu);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mailEdu->setDateEnvoi(new \DateTimeImmutable());
                $emailEducateur= $form->get('emailEducateur')->getData();
                $query = "SELECT numero FROM educateur WHERE email = :email";
                $statement = $manager->getConnection()->executeQuery($query, ['email' => $emailEducateur]);
                $result = $statement->fetchFirstColumn();
                 if ($result) {
                $idEducateu = $result[0];
                $mailEdu->setEducateur($idEducateu);
                $mailEdu->addEducateur($idEducateu);
                // Définissez les autres propriétés en fonction des données du formulaire
                $manager->persist($mailEdu);
                $manager->flush();

                $email = (new Email())
                    ->from(new Address('kboumar17@gmail.com'))
                    ->to( new Address($emailEducateur))
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject($mailEdu->getObjet())
                    ->text(body:$mailEdu->getMessage())
               // ->html('<p>See Twig integration for better HTML integration!</p>');
               ;
                $mailer->send($email);
                $this->addFlash(
                    'success','Email Envoyé avec succès !'
                );
                return $this->redirectToRoute('app_mail_edu');
        } else {
            $this->addFlash(
                'error', "Ce email n'existe pas dans les emails des contacts."
            );
            return $this->render('educateur/email.html.twig', [
                'form' => $form->createView(),'emails'=>$formattedEmailEducateur
            ]);
        }
       
        }

      
        return $this->render('educateur/email.html.twig', [
            'form' => $form->createView(),'emails'=>$formattedEmailEducateur
        ]);
    }

 

}