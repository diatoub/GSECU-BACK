<?php
namespace App\Model;

use App\Entity\BeneficiaireQrcode;
use App\Entity\CategorieDossier;
use App\Entity\Commentaire;
use App\Entity\ComplementDossier;
use App\Entity\Dossier;
use App\Entity\Etat;
use App\Entity\MotifDemande;
use App\Entity\ObjetBadge;
use App\Entity\Profil;
use App\Entity\Site;
use App\Entity\SuiviDelai;
use App\Entity\TypeBadge;
use App\Entity\TypeContrat;
use App\Entity\TypeDossier;
use App\Entity\TypeMateriel;
use App\Entity\User;
use App\Mapping\DossierMapping;
use App\Model\Base\BaseManager;
use App\Service\Fonctions;
use App\Utils\GenerateUtils;
use Doctrine\Persistence\ManagerRegistry;

class DossierManager extends BaseManager {

    protected $em;
    protected $dossierMapping;
    protected $fonctions;
    protected $generateUtils;
    public function __construct(ManagerRegistry $doctrine, DossierMapping $dossierMapping, Fonctions $fonctions, GenerateUtils $generateUtils) 
    {
        $this->em = $doctrine->getManager();
        $this->dossierMapping = $dossierMapping;
        $this->fonctions = $fonctions;
        $this->generateUtils = $generateUtils;
    }

    public function lesDossiers($userConnect, $categorie, $codeDossier, $dateDebut, $dateFin, $page,$limit,$filtre,$etat, $site){
        $offset=$limit!='ALL'?($page - 1) * $limit:$_ENV["LIMIT"];
        $find_site = $site ? $this->em->getRepository(Site::class)->find($site) : null ;
        $my_site = $find_site ? $find_site->getLibelle() :null ;        
        $find_etat = $etat ? $this->em->getRepository(Etat::class)->find($etat) : null ;
        $my_etat = $find_etat ? $find_etat->getLibelle() :null ;
        $categorie = $categorie ? $this->em->getRepository(CategorieDossier::class)->find($categorie) : null ;
        $catgorieDossier = $categorie ? $categorie->getCode() :null ;
        $les_dossiers = $this->em->getRepository(Dossier::class)->lesDossiers($catgorieDossier, $codeDossier, $dateDebut, $dateFin, $offset,$limit,$filtre,$my_etat, $my_site);
        $total = $this->em->getRepository(Dossier::class)->countDossiers($catgorieDossier, $codeDossier, $dateDebut, $dateFin, $offset,$limit,$filtre,$my_etat, $my_site);
        return $this->sendResponse(true, 200, $les_dossiers, $total);
    }
    
    public function detailDossier($userConnect, $id, $categorie){
        $my_categorie = isset($categorie) ? $categorie : null;
        $my_dossier = $id ? $this->em->getRepository(Dossier::class)->find($id) : null ;
        $pieces_jointes = $this->em->getRepository(ComplementDossier::class)->getComplementByDossier($id);
        $preuve = $this->em->getRepository(Commentaire::class)->getCommentaireByDossier($id);
        if (!$my_dossier) {
            return $this->sendResponse(false, 404, "Dossier introuvable");            
        }
        $etats = [Etat::NOUVEAU, Etat::OUVERT];
        if (in_array($my_dossier->getEtat()->getLibelle(), $etats)) {
            $executeur = array();
        } 
        elseif ($my_dossier->getAgentExecution()){
            $executeur = $this->dossierMapping->mappingInfoUser($my_dossier->getAgentExecution());
        } 
        else{
            $executeur = $this->em->getRepository(User::class)->getUserByDossier($id, [Profil::EXECUTEUR, Profil::DGSECU]);
        }
        
        $isValide = [Etat::VALIDE, Etat::SIGNE, Etat::CLOTURE];
        if (!in_array($my_dossier->getEtat()->getLibelle(), $isValide)) {
            $validateur = array();
        } 
        elseif ($my_dossier->getValidateur()){
            $validateur = $this->dossierMapping->mappingInfoUser($my_dossier->getValidateur());       
        } 
        else{
            $validateur = $this->em->getRepository(User::class)->getUserByDossier($id, [Profil::EXECUTEUR, Profil::SUPER_AGENT, Profil::DGSECU]);
        }
        $administrateur = $this->em->getRepository(User::class)->getUserByDossier($id, [Profil::SUPER_AGENT, Profil::ADMINISTRATEUR, Profil::DGSECU, Profil::SUPER_ADMINISTRATEUR, Profil::SUPER_AGENT]);
        $currentAdmin = false;
        foreach ($administrateur as $admin) {
            if ($admin['id'] === $userConnect->getId()){
                $currentAdmin = true;
            }
        }
        
        // add data on log file
        $dossier = $my_dossier->getTypeDossier() ? $my_dossier->getTypeDossier()->getLibelle() : '';
        $info_demandeur = ['libelle' => $my_dossier->getLibelle(), 'type_demande' => $dossier, 'prenom_demandeur' => $my_dossier->getFirstname(), 'nom_demandeur' => $my_dossier->getLastname(), 'numero_demandeur' => $my_dossier->getMobile()];
        $info_beneficiaire = ['prenom_beneficiaire' => $my_dossier->getNomBeneficiaire(), 'nom_beneficiaire' => $my_dossier->getPrenomBeneficiaire(), 'matricule_beneficiaire' => $my_dossier->getMatriculeBeneficiaire()];
        $infoDossier = $this->dossierMapping->mappingDossier($my_dossier);
        if ($infoDossier['categorie'] != $my_categorie) {
            return $this->sendResponse(false, 404, "Ce dossier n'est pas dans sa catégorie");
        }
        return $this->sendResponse(true, 200, 
        array(
            'my_dossier' => $infoDossier,
            'executeur' => $executeur,
            'administrateur' => $administrateur,
            'validateur' => $validateur,
            'currentAdmin' => $currentAdmin,
            'pieces_jointes' => $pieces_jointes,
            'preuve'	=> $preuve,
            'info_demandeur' => $info_demandeur,
            'info_beneficiaire' => $info_beneficiaire
        ));        
    }

    public function check_dossier($userConnect, $codeDossier){
        $my_dossier = $codeDossier ? $this->em->getRepository(Dossier::class)->findOneBy(['codeDossier' => $codeDossier]) : null ;
        if (!$my_dossier) {
            return $this->sendResponse(false, 503, "Dossier introuvable");            
        }
        $pieces_jointes = $this->em->getRepository(ComplementDossier::class)->getComplementByDossier($my_dossier->getId());
        $preuve = $this->em->getRepository(Commentaire::class)->getCommentaireByDossier($my_dossier->getId());
        $etats = [Etat::NOUVEAU, Etat::OUVERT];
        if (in_array($my_dossier->getEtat()->getLibelle(), $etats)) {
            $executeur = array();
        } 
        elseif ($my_dossier->getAgentExecution()){
            $executeur = $this->dossierMapping->mappingInfoUser($my_dossier->getAgentExecution());
        } 
        else{
            $executeur = $this->em->getRepository(User::class)->getUserByDossier($my_dossier->getId(), [Profil::EXECUTEUR, Profil::DGSECU]);
        }
        
        $isValide = [Etat::VALIDE, Etat::SIGNE, Etat::CLOTURE];
        if (!in_array($my_dossier->getEtat()->getLibelle(), $isValide)) {
            $validateur = array();
        } 
        elseif ($my_dossier->getValidateur()){
            $validateur = $this->dossierMapping->mappingInfoUser($my_dossier->getValidateur());       
        } 
        else{
            $validateur = $this->em->getRepository(User::class)->getUserByDossier($my_dossier->getId(), [Profil::EXECUTEUR, Profil::SUPER_AGENT, Profil::DGSECU]);
        }
        $administrateur = $this->em->getRepository(User::class)->getUserByDossier($my_dossier->getId(), [Profil::SUPER_AGENT, Profil::ADMINISTRATEUR, Profil::DGSECU, Profil::SUPER_ADMINISTRATEUR, Profil::SUPER_AGENT]);
        $currentAdmin = false;
        foreach ($administrateur as $admin) {
            if ($admin['id'] === $userConnect->getId()){
                $currentAdmin = true;
            }
        }
        
        // add data on log file
        $dossier = $my_dossier->getTypeDossier() ? $my_dossier->getTypeDossier()->getLibelle() : '';
        $info_demandeur = ['libelle' => $my_dossier->getLibelle(), 'type demande' => $dossier, 'Prenom demandeur' => $my_dossier->getFirstname(), 'Nom demandeur' => $my_dossier->getLastname(), 'numéro demandeur' => $my_dossier->getMobile()];
        $info_beneficiaire = ['Prenom beneficiaire' => $my_dossier->getNomBeneficiaire(), 'Nom beneficiaire' => $my_dossier->getPrenomBeneficiaire(), 'matricule beneficiaire' => $my_dossier->getMatriculeBeneficiaire()];
        $infoDossier = $this->dossierMapping->mappingDossier($my_dossier);
        return $this->sendResponse(true, 200, 
        array(
            'my_dossier' => $infoDossier,
            'executeur' => $executeur,
            'administrateur' => $administrateur,
            'validateur' => $validateur,
            'currentAdmin' => $currentAdmin,
            'pieces_jointes' => $pieces_jointes,
            'preuve'	=> $preuve,
            'info_demandeur' => $info_demandeur,
            'info_beneficiaire' => $info_beneficiaire
        ));        
    }

    public function clotureAction($userConnect, $id) {
        $profil = $userConnect->getProfil() ? $userConnect->getProfil()->getCode() : null;
        if ($profil != Profil::ADMINISTRATEUR  && $profil != Profil::SUPER_ADMINISTRATEUR && $profil != Profil::SUPER_AGENT) {
            return $this->sendResponse(false, 503, array('message' => "Vous n'êtes pas autorisés à faire cet action"));
        }
        $my_dossier = $id ? $this->em->getRepository(Dossier::class)->find($id) : null ;
        if (!$my_dossier) {
            return $this->sendResponse(false, 404, "Dossier introuvable");      
        }
        $administrateur = $this->em->getRepository(User::class)->getUserByDossier($id, [Profil::SUPER_AGENT, Profil::ADMINISTRATEUR, Profil::DGSECU, Profil::SUPER_ADMINISTRATEUR, Profil::SUPER_AGENT]);
        $currentAdmin = false;
        $emailAdmin = null;
        foreach ($administrateur as $admin) {
            $emailAdmin = $admin ? $admin['email'] : null;
            if ($admin['id'] === $userConnect->getId()){
                $currentAdmin = true;
            }
        }
        $signaleur = $my_dossier->getEmail();
        if ($my_dossier->getEtat()->getLibelle() !== Etat::SIGNE) { 
            return $this->sendResponse(false, 503, "Une signature est requis pour clôturer le dossier !");
        }
        if ($currentAdmin) {
            // passage au traitement            
            $etatDossier = $this->em->getRepository(Etat::class)->findOneBy(['libelle'=>Etat::CLOTURE]);
            $my_dossier->setEtat( $etatDossier);
            !$my_dossier->getAgentExecution() ? $my_dossier->setAgentExecution($userConnect) : null;
            !$my_dossier->getValidateur() ? $my_dossier->setValidateur($userConnect) : null;
            $this->em->persist($my_dossier);
            $suiviDelai = $this->em->getRepository(SuiviDelai::class)->findOneByDossier($my_dossier);
            $delaiRequis = $this->em->getRepository(Dossier::class)->getNombreJourRequis($my_dossier->getId());            
            $suiviDelai->setDateCloture(new \DateTime());
            $dateOuverture = $suiviDelai->getDateOuverture();
            $interval = $dateOuverture->diff(new \DateTime)->days;
            $suiviDelai->setInterval($interval);
            if($delaiRequis['nbreJoursLivraison'] < $interval){
                $suiviDelai->setIsHorsDelai(true);
            }
                $recepteursMail = array($emailAdmin, $signaleur, $my_dossier->getAgentExecution()->getEmail(), $my_dossier->getValidateur()->getEmail());

                $this->em->persist($suiviDelai);
                $this->em->flush();
                $msg=array(
                    "to"=>$recepteursMail,
                    "body"=>$this->fonctions->setMailClotureDossier($my_dossier),
                    "subject"=>"CLOTURE DOSSIER",
                    "cc"=>$this->copy,
                );
                $this->fonctions->sendMail($msg);
                return $this->sendResponse(true,200,array('message'=>'Le dossier '.$my_dossier->getTypeDossier()->getLibelle().' clôturé avec succès !'));
            } else {
                return $this->sendResponse(false, 503, "Vous n'êtes pas l'administrateur de ce dossier!");
        }  
    }
    
    public function nouvelleSignalisation($userConnect, $post) {
        $entity = new Dossier();
        $signaleur = isset($post['signaleur']) ? $this->em->getRepository(User::class)->find($post['signaleur']) : null ;
        // tester si cest en mode post notify
            $user = $userConnect ? $userConnect : $signaleur;
            if ($user) {
                $entity->setLastname($user->getNom());
                $entity->setFirstname($user->getPrenom());
                $entity->setMobile($user->getTelephone());
                $entity->setEmail($user->getEmail());
                $entity->setStructure($user->getStructure());
            }
            $libelle = isset($post['libelle']) ? $post['libelle'] : null;
            $autre_materiel = isset($post['autre_materiel']) ? $post['autre_materiel'] : null;
            $description_panne = isset($post['description_panne']) ? $post['description_panne'] : null;
            $quantite_panne = isset($post['quantite_panne']) ? $post['quantite_panne'] : null;
            $site = isset($post['site']) ? $this->em->getRepository(Site::class)->find($post['site']) : null;
            $type_signalisation = isset($post['type_signalisation']) ? $this->em->getRepository(TypeDossier::class)->find($post['type_signalisation']) : null;
            $materiel_panne = isset($post['materiel_panne']) ? $this->em->getRepository(TypeMateriel::class)->find($post['materiel_panne']) : null;
            $site = isset($post['site']) ? $this->em->getRepository(Site::class)->find($post['site']) : null;
            if (!$materiel_panne) {
                return $this->sendResponse(false, 404, "Materiel en panne introuvable");
            }
            if (!$type_signalisation) {
                return $this->sendResponse(false, 404, "Type de signalisation introuvable");
            }
            if (!$site) {
                return $this->sendResponse(false, 404, "Site introuvable");
            }
            $entity->setSite($site);
            $entity ->setTypeDossier($type_signalisation);
            $entity->setTypeMateriel($materiel_panne);
            $entity->setLibelle($libelle);
            $entity->setAutreMateriel($autre_materiel);
            $entity->setQuantite($quantite_panne);
            $entity->setDescription($description_panne);
            $entity->setDateAjout(new \DateTime());
           if (isset($post['file'])) {
                foreach ($post['file'] as $fichier){ //Permet d'obtenir le path des fichiers uploadés
                    $complement = new ComplementDossier();
                    $complement->file = $fichier;
                    $complement->preUpload();
                    $complement->upload($post['document_directory']);
                    $complement->setLibelle(isset($post['libellePiece']) ? $post['libellePiece'] : null);
                    $entity->addComplementDossier($complement);
                    $this->em->persist($complement);
                }
            }
            $etatDossier = $this->em->getRepository(Etat::class)->findOneBy(['libelle'=>Etat::NOUVEAU]);
            $entity->setEtat( $etatDossier );
            $unique_id =$this->generateUtils->newKey($this->em);
            $code = "SIG".date("Y").date("m").$unique_id['uniqueDossier'];
            // Cryptage du code du dossier en md5
            $codeSecret = md5($code);
            $entity->setCodeDossier($code);
            $entity->setCodeSecret($codeSecret);
            $admin_and_executeur = $this->em->getRepository(User::class)->getAdminAndExecuteur(true);
            $email = "salifabdoul.sow1@orange-sonatel.com,ababacar.fall@orange-sonatel.com,malick.coly1@orange-sonatel.com";
            $recepteursMail = explode(',', $email);
            // $recepteursMail = explode(',', $admin_and_executeur[0]["emailAdmin"]);

            $this->em->persist($entity);
            $this->em->flush();
            // Envoie de mail à tous les admins pour notifier du nouveau dossier
            // Envoi de mail à tous les administrateurs
            $msg=array(
                "to"=>$recepteursMail,
                "body"=>$this->fonctions->setMailAdminNouvelleDossier($entity),
                "subject"=>"NOUVELLE SIGNALISATION",
                "cc"=>$this->copy,
            );
            $msg1=array(
                "to"=>$entity->getEmail(),
                "body"=>$this->fonctions->setMailSignaleurNouvelleDossier($entity),
                "subject"=>"NOUVELLE SIGNALISATION",
                "cc"=>$this->copy,
            );
            $this->fonctions->sendMail($msg);
            $this->fonctions->sendMail($msg1);
            return $this->sendResponse(true,200,array('message'=>'Une nouvelle signalisation intitulée '.$entity->getTypeDossier()->getLibelle().' ajoutée avec succès !'));
            

    }

    public function nouvelleDemande($userConnect, $post) {
        $entity = new Dossier();
        $type_demande = isset($post['type_demande']) ? $this->em->getRepository(TypeDossier::class)->find($post['type_demande']) : null;
        if (!$type_demande) {
            return $this->sendResponse(false, 404, "Type de demande introuvable");
        }
        $demandeur = isset($post['demandeur']) ? $this->em->getRepository(User::class)->find($post['demandeur']) : null ;
        // tester si cest en mode post notify
            $user = $userConnect ? $userConnect : $demandeur;
            if ($user) {
                $entity->setLastname($user->getNom());
                $entity->setFirstname($user->getPrenom());
                $entity->setMobile($user->getTelephone());
                $entity->setEmail($user->getEmail());
                $entity->setStructure($user->getStructure());
            }
            $entity->setDateAjout(new \DateTime());
            if (isset($post['file'])) {
                foreach ($post['file'] as $fichier){ //Permet d'obtenir le path des fichiers uploadés
                    $complement = new ComplementDossier();
                    $complement->file = $fichier;
                    $complement->preUpload();
                    $complement->upload($post['document_directory']);
                    $complement->setLibelle(isset($post['libellePiece']) ? $post['libellePiece'] : null);
                    $entity->addComplementDossier($complement);
                    $this->em->persist($complement);
                }
            }
            $etatDossier = $this->em->getRepository(Etat::class)->findOneBy(['libelle'=>Etat::NOUVEAU]);
            $entity->setEtat( $etatDossier );
            $unique_id =$this->generateUtils->newKey($this->em);
            $code = "DEM".date("Y").date("m").$unique_id['uniqueDossier'];
            // Cryptage du code du dossier en md5
            $codeSecret = md5($code);
            $entity->setCodeDossier($code);
            $entity->setCodeSecret($codeSecret);
            $description = isset($post['description']) ? $post['description'] : null;
            $entity->setDescription($description);
            $entity ->setTypeDossier($type_demande);

            // Traitement des différentes formulaires (les formulaires en communs)
            if ($type_demande->getId() != 22 && $type_demande->getId() != 31) {
                $quantite = isset($post['quantite'] ) ? $post['quantite'] : null;
                $entity->setQuantite($quantite);   
            }
            // Traitement du formulaire (Confection de badge)
            if ($type_demande->getId() === 22) {
                $nom = isset($post['nom']) ? $post['nom'] : null;
                $prenom = isset($post['prenom']) ? $post['prenom'] : null;
                $matricule = isset($post['matricule']) ? $post['matricule'] : null;
                $site = isset($post['site']) ? $this->em->getRepository(Site::class)->find($post['site']) : null;
                $type_badge = isset($post['type_badge']) ? $this->em->getRepository(TypeBadge::class)->find($post['type_badge']) : null;
                $type_contrat = isset($post['type_contrat']) ? $this->em->getRepository(TypeContrat::class)->find($post['type_contrat']) : null;
                $objet_badge = isset($post['objet_badge']) ? $this->em->getRepository(ObjetBadge::class)->find($post['objet_badge']) : null;
                $motif_demande = isset($post['motif_demande']) ? $this->em->getRepository(MotifDemande::class)->find($post['motif_demande']) : null;
                $motif_remplacement = isset($post['motif_remplacement']) ? $this->em->getRepository(MotifDemande::class)->find($post['motif_remplacement']) : null;
                
                if (!$type_badge) {
                    return $this->sendResponse(false, 404, "Type de badge introuvable");
                }
                if (!$site) {
                    return $this->sendResponse(false, 404, "Site introuvable");
                }
                if (!$type_contrat) {
                    return $this->sendResponse(false, 404, "Type de contrat introuvable");
                }
                if (!$objet_badge) {
                    return $this->sendResponse(false, 404, "Objet badge introuvable");
                }
                if (!$motif_demande) {
                    return $this->sendResponse(false, 404, "Motif de demande introuvable");
                }
                if (!$motif_remplacement) {
                    return $this->sendResponse(false, 404, "Motif de remplacement introuvable");
                }
                $entity->setNomBeneficiaire($nom);
                $entity->setPrenomBeneficiaire($prenom);
                $entity->setMatriculeBeneficiaire($matricule);
                $entity->setSiteBeneficiaire($site);
                $entity->setTypeBadge($type_badge);
                $entity->setTypeContrat($type_contrat);
                $entity->setObjetBadge($objet_badge);
                $entity->setMotifDemande($motif_demande);
                $entity->setMotifRemplacement($motif_remplacement);
            }
            // Traitement du formulaire (Demande d'autorisation d'accès)
            if ($type_demande->getId() === 31) {
                $fichiertmp = $entity->getFileBeneficiaires()?$entity->getFileBeneficiaires()->getRealPath():null;//Récupère le chemin temporaire sur la machine cliente
                $typeFichier = $entity->getFileBeneficiaires()?$entity->getFileBeneficiaires()->getClientMimeType():null;
                if($typeFichier && ($typeFichier=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $typeFichier=="application/vnd.ms-excel")){
                    $data = $this->get('orange.main.loader')->excelToArray($fichiertmp, true);
                }
                else{
                    $data = $this->get('orange.main.loader')->csvToArray($fichiertmp, ';');
                }
                if($data){
                    foreach ($data as $dt){
                            $nom = array('nom' => $dt['Prénom']. ' '.$dt['Nom']);
                            $entity->addBeneficiaire($nom);
                            $beneficiaireQrcode= new BeneficiaireQrcode();
                            $beneficiaireQrcode->setToken(md5(uniqid()));
                            $beneficiaireQrcode->setNumero($dt['Numéro']);
                            $beneficiaireQrcode->setPrenom($dt['Prénom']);
                            $beneficiaireQrcode->setNom($dt['Nom']);
                            $beneficiaireQrcode->setEmail($dt['Email']);
                            $beneficiaireQrcode->setDossier($entity);
                            $beneficiaireQrcode->setIsInterne('true');
                            $beneficiaireQrcode->setSendQrcode('true');
                            $this->em->persist($beneficiaireQrcode);
                    }
                }
            }            
            $admin_and_executeur = $this->em->getRepository(User::class)->getAdminAndExecuteur(true);
            $email = "salifabdoul.sow1@orange-sonatel.com,ababacar.fall@orange-sonatel.com,malick.coly1@orange-sonatel.com";
            $recepteursMail = explode(',', $email);
            // $recepteursMail = explode(',', $admin_and_executeur[0]["emailAdmin"]);

            $this->em->persist($entity);
            $this->em->flush();
            // Envoie de mail à tous les admins pour notifier du nouveau dossier
            // Envoi de mail à tous les administrateurs
            $msg=array(
                "to"=>$recepteursMail,
                "body"=>$this->fonctions->setMailAdminNouvelleDossier($entity),
                "subject"=>"NOUVELLE DEMANDE",
                "cc"=>$this->copy,
            );
            $msg1=array(
                "to"=>$entity->getEmail(),
                "body"=>$this->fonctions->setMailSignaleurNouvelleDossier($entity),
                "subject"=>"NOUVELLE DEMANDE",
                "cc"=>$this->copy,
            );
            $this->fonctions->sendMail($msg);
            $this->fonctions->sendMail($msg1);
            return $this->sendResponse(true,200,array('message'=>'Une nouvelle demande intitulée '.$entity->getTypeDossier()->getLibelle().' ajoutée avec succès !'));
            
    }
}

?>