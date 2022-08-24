<?php
namespace App\Model;

use App\Entity\CategorieDossier;
use App\Entity\Commentaire;
use App\Entity\ComplementDossier;
use App\Entity\Dossier;
use App\Entity\Etat;
use App\Entity\Profil;
use App\Entity\Site;
use App\Entity\SuiviDelai;
use App\Entity\User;
use App\Mapping\DossierMapping;
use App\Model\Base\BaseManager;
use App\Service\Fonctions;
use Doctrine\Persistence\ManagerRegistry;

class DossierManager extends BaseManager {

    protected $em;
    protected $dossierMapping;
    protected $fonctions;
    public function __construct(ManagerRegistry $doctrine, DossierMapping $dossierMapping, Fonctions $fonctions) 
    {
        $this->em = $doctrine->getManager();
        $this->dossierMapping = $dossierMapping;
        $this->fonctions = $fonctions;
    }

    public function lesDossiers($userConnect, $categorie, $codeDossier, $dateDebut, $dateFin, $page,$limit,$filtre,$etat, $site){
        $offset=$limit!='ALL'?($page - 1) * $limit:$_ENV["LIMIT"];
        $find_site = $site ? $this->em->getRepository(Site::class)->find($site) : null ;
        $my_site = $find_site ? $find_site->getLibelle() :null ;        
        $find_etat = $etat ? $this->em->getRepository(Etat::class)->find($etat) : null ;
        $my_etat = $find_etat ? $find_etat->getLibelle() :null ;
        $categorie = $categorie ? $this->em->getRepository(CategorieDossier::class)->find($categorie) : null ;
        $catgorieDossier = $categorie ? $categorie->getCode() :null ;
        // dd($catgorieDossier);
        $les_dossiers = $this->em->getRepository(Dossier::class)->lesDossiers($catgorieDossier, $codeDossier, $dateDebut, $dateFin, $offset,$limit,$filtre,$my_etat, $my_site);
        $total = $this->em->getRepository(Dossier::class)->countDossiers($catgorieDossier, $codeDossier, $dateDebut, $dateFin, $offset,$limit,$filtre,$my_etat, $my_site);
        return $this->sendResponse(true, 200, $les_dossiers, $total);
    }
    
    public function detailDossier($userConnect, $id){
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
    public function check_dossier($userConnect, $codeDossier){
        $my_dossier = $codeDossier ? $this->em->getRepository(Dossier::class)->findOneBy(['codeDossier' => $codeDossier]) : null ;
        $pieces_jointes = $this->em->getRepository(ComplementDossier::class)->getComplementByDossier($my_dossier->getId());
        $preuve = $this->em->getRepository(Commentaire::class)->getCommentaireByDossier($my_dossier->getId());
        if (!$my_dossier) {
            return $this->sendResponse(true, 200, "Dossier introuvable");            
        }
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

    public function clotureAction($userConnect, $post, $id,) {
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
}

?>