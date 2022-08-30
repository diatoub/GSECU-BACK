<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\TypeMateriel;
use FOS\RestBundle\Controller\Annotations as Rest;

class TypeMaterielController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_types_materiels", name="les_types_materiels")
     * @QMLogger(message="listes types materiels")
     */
    public function listeTypeMateriel() {
        return $this->sendResponse(true, 200,$this->em->getRepository(TypeMateriel::class)->listeTypeMateriel());
    }
    
}

?>