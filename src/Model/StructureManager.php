<?php
namespace App\Model;

use App\Entity\Structure;
use App\Entity\TypeStructure;
use App\Model\Base\BaseManager;
use Doctrine\Persistence\ManagerRegistry;

class StructureManager extends BaseManager {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }

    public function lesStructures($userConnect, $id, $type){
        $typeStructure = $id ? $this->em->getRepository(TypeStructure::class)->find($id) : null ;
        $my_typeStructure= $typeStructure ? $typeStructure->getLibelle() :null ;
        $les_structures = $this->em->getRepository(Structure::class)->lesStructures($my_typeStructure, $type);
        return $this->sendResponse(true, 200, $les_structures);
    }

}

?>