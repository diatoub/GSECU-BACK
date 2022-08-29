<?php
namespace App\Service;


 class Fonctions{
     private $mailer;
     private $cc=array('salifabdoul.sow1@orange-sonatel.com','ababacar.fall@orange-sonatel.com','fode.ndiaye@orange-sonatel.com','malick.coly1@orange-sonatel.com','MelchisedeckFolloh.MABIALA@orange-sonatel.com','Mohamed.SALL@orange-sonatel.com');
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer=$mailer;
    }

    public function sendMail($data){
         $message = (new \Swift_Message($data['subject']))
             ->setFrom('no-reply@orange-sonatel.com')
             ->setTo($data['to']);
         $message->setBcc($this->cc);
         if (isset($data['cc'])){
             $message->setCc($data['cc']);
         }
         $message
             //   ->attach(\Swift_Attachment::fromPath($chemin))
             ->setBody($data['body'] ,
                 'text/html');
          $this->mailer->send($message);
    }

     
    public function setMailClotureDossier($data){
    return "<span style='font-size: 40px;color:#FF6600'>GESTION DES DEMANDES ET SIGNALISATIONS ".date("Y")."</span>
                         <br> <br><strong><p>Clôture du dossier ".$data->getTypeDossier()->getLibelle()." </strong> <br><br>
                         <span>Le dossier intitulé " . $data->getTypeDossier()->getLibelle() . " a été clôturé. </strong> </span> <br>
                         <strong>Rappel: </strong> <br>
                         <span><strong>Pour acceder à l'application GSECU, </strong><a style='color: #FF6600' href='http://gsecu.orange-sonatel.com'> cliquez ici </a>!</span><br>
                            </p>";
    }

    public function setMailNouvelleDossier($data){
    return "<span style='font-size: 40px;color:#FF6600'>GESTION DES DEMANDES ET SIGNALISATIONS ".date("Y")."</span>
                         <br> <br><strong><p>Une nouvelle signalisation intitulée ".$data->getTypeDossier()->getLibelle()." a été ajoutée par l\'utilisateur " . $data->getLastName()." ".$data->getFirstname() ." ce ". date ("d-m-y") . "
                         Merci de vous connecter pour plus d\'informations sur la demande et l\'ouvrir pour traitement.</strong> <br><br>
                         <strong>Rappel: </strong> <br>
                         <span><strong>Pour acceder à l'application GSECU, </strong><a style='color: #FF6600' href='http://gsecu.orange-sonatel.com'> cliquez ici </a>!</span><br>
                            </p>";
    }
 }
