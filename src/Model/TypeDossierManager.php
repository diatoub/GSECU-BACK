<?php
namespace App\Model;

use App\Entity\CategorieDossier;
use App\Entity\TypeDossier;
use App\Model\Base\BaseManager;
use Doctrine\Persistence\ManagerRegistry;

class TypeDossierManager extends BaseManager {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }

    public function lesTypesDossiers($userConnect, $post){
        $catgorieDossier = isset($post['id']) ? $this->em->getRepository(CategorieDossier::class)->find($post['id']) : null ;
        $catgorie = $catgorieDossier ? $catgorieDossier->getCode() :null ;
        $les_equipements = $this->em->getRepository(TypeDossier::class)->lesTypesDossiers($catgorie);
        return $this->sendResponse(true, 200, $les_equipements);
    }

}

?>