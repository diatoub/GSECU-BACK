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
    public function setMailRelanceFournisseur($user){
    return "<span style='font-size: 40px;color:#FF6600'>QREDIC</span>
                         <br> <br><strong><p> Rappel d'échéances </strong> <br><br>
                         <span>Bonjour ". $user["nomComplet_Fournisseur"].", </span> <br>
                         <span style='color:#FF6600'>Ceci est un mail de notification pour la saisi de vos recos.</span> <br>
                         <strong>Rappel: </strong> <br>
                         <span>Votre nom d'utilisateur : ". $user["username_Fournisseur"]." </span> <br>
                         <span><strong>Pour acceder à l'application QREDIC, </strong><a style='color: #FF6600' href='http://qredic.orange-sonatel.com'> cliquez ici </a>!</span><br>
                            </p>";
    }

    public function setMailAcces($data){
        return "<span style='font-size: 40px;color:#FF6600'>QREDIC ".date("Y")."</span>
                         <br> <br><strong><p>Accès QREDIC </strong> <br><br>
                         <span>Bonjour ". $data["nomComplet"].", </span> <br>
                         <span>Voici vos identifiants pour accèder à la plateforme </span> <br>
                         <span>Votre nom d'utilisateur : ". $data["username"]." </span> <br>
                         <span>Votre mot de passe : ". $data["password"]." </span> <br>
                         <span><strong>Pour acceder à l'application QREDIC, </strong><a style='color: #FF6600' href='http://qredic.orange-sonatel.com'> cliquez ici </a>!</span><br>
                         </p>";
    }

    public function validateFile($file){
        //  var_dump($file->guessExtension());exit;
        $allowed  = ['csv', 'txt'];
        if ($file){
            if (in_array($file->guessExtension(),$allowed)){
                return true;
            }
            else{
                return false;
            }
        }else{
            return false;
        }

    }


    public function uploadFile($file, $directory, $allowed){
        //  $allowed = ['jpg', 'jpeg', 'png', 'gif','pdf','PDF','JPG','JPEG','PNG'];
         if ($file){
             $fichier = md5(uniqid()).'.'.$file->guessExtension();
             $bool=false;
             if (in_array($file->guessExtension(),$allowed)){
                 $bool=true;
             }
             if ($bool){
                 $file->move(
                     $directory,
                     $fichier
                 );
             }
             return array(
                 "filename"=>$fichier,
                 "isValid"=>$bool
             );
         }else{
             return array(
                 "filename"=>null,
                 "isValid"=>false
             );
         }

    }

 }
