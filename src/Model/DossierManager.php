<?php
namespace App\Model;

use App\Entity\CategorieDossier;
use App\Entity\Dossier;
use App\Entity\Etat;
use App\Entity\Site;
use App\Model\Base\BaseManager;
use Doctrine\Persistence\ManagerRegistry;

class DossierManager extends BaseManager {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
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
        $catgorieDossier = $id ? $this->em->getRepository(CategorieDossier::class)->find($id) : null ;
        $categorie = $catgorieDossier ? $catgorieDossier->getCode() :null ;
        $details_dossiers = $this->em->getRepository(Dossier::class)->detailDossier($categorie);
        return $this->sendResponse(true, 200, $details_dossiers);
    }

}

?>