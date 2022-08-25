<?php
namespace App\Model;

use App\Entity\Equipement;
use App\Entity\Profil;
use App\Mapping\EquipementMapping;
use App\Model\Base\BaseManager;
use Doctrine\Persistence\ManagerRegistry;

class EquipementManager extends BaseManager {

    protected $equipementM;
    protected $em;
    public function __construct( EquipementMapping $equipementM, ManagerRegistry $doctrine) 
    {
        $this->equipementM = $equipementM;
        $this->em = $doctrine->getManager();
    }

    public function lesEquipement($page,$limit,$userConnect,$filtre){
        $offset=$limit!='ALL'?($page - 1) * $limit:$_ENV["LIMIT"];
        $les_equipements =$this->em->getRepository(Equipement::class)->listEquipements($limit,$offset,$filtre);
        $total = $this->em->getRepository(Equipement::class)->countEquipements($filtre);
        return $this->sendResponsePagination($this->equipementM->listeEquipements($les_equipements),$total);
    }

    public function addEquipement($data, $userConnect, $action) {
        $id = $action ==  $this::EDIT ? $data['id'] : null;
        $profil = $userConnect->getProfil() ? $userConnect->getProfil()->getCode() : null;
        if ($profil != Profil::ADMINISTRATEUR  && $profil != Profil::SUPER_ADMINISTRATEUR && $profil != Profil::SUPER_AGENT && $profil != Profil::EXECUTEUR) {
            return $this->sendResponse(false, 503, array('message' => "Vous n'êtes pas autorisés à faire cet action"));
        }
        $equipement = $action == $this::ADD ? new Equipement() : $this->em->getRepository(Equipement :: class)->find($data['id']);
        $message = $action ==  $this::ADD ? $this::AJOUTE : $this::UPDATE;
        if ($equipement == null) {
            return $this->sendResponse(false, 501, array('message' => "Equipement introuvable!"));           
        }        
        $equipement =  $this->equipementM->setEquipementData($data, $equipement);
        if(!$equipement->getLibelle()){
            return $this->sendResponse(false, 501, array('message' => "Le libelle est obligatoire!"));
        }
        $isExist = $this->em->getRepository(Equipement :: class)->findOneBy(['libelle'=>$equipement->getLibelle()]);
        if ($isExist &&  $id) {
            if ($isExist->getId() != $id) {
                return $this->sendResponse(false, 501, array('message' => "Equipement existe déjà!"));
            }
        }elseif ($isExist && !$id) {
            return $this->sendResponse(false, 501, array('message' => "Equipement existe déjà!"));
        }
        $this->em->persist($equipement);
        $this->em->flush();        
        return $this->sendResponse(true, 200, array('message' =>'Equipement '.$message.' avec succès !' ));        
    }

    public function deleteEquipement($id,$userConnect) {
        $profil = $userConnect->getProfil() ? $userConnect->getProfil()->getCode() : null;
        if ($profil != Profil::ADMINISTRATEUR  && $profil != Profil::SUPER_ADMINISTRATEUR && $profil != Profil::SUPER_AGENT && $profil != Profil::EXECUTEUR) {
            return $this->sendResponse(false, 503, array('message' => "Vous n'êtes pas autorisés à faire cet action"));
        }
        $equipement = $this->em->getRepository(Equipement::class)->find($id);
        if ($equipement == null) { 
            return $this->sendResponse(false, 501, array('message'=>"Equipement introuvable!")); 
        }
        try {
            $this->em->remove($equipement);
            $this->em->flush();
            return $this->sendResponse(true, 200, array('message'=>"Equipement supprimé avec succès"));
        } catch (\Exception $e) {
            return $this->sendResponse(false, 500, array('message'=>"Impossible de supprimé cet équipement!"));
        }
    }


}

?>