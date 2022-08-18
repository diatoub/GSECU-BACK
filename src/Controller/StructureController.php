<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Model\StructureManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class StructureController extends BaseController {

    protected $em;
    protected $structureManager;
    public function __construct(ManagerRegistry $doctrine, StructureManager $structureManager) 
    {
        $this->em = $doctrine->getManager();
        $this->structureManager = $structureManager;
    }


    /**
     * @Rest\Get("/les_structures", name="les_structures")
     * @QMLogger(message="listes des structures")
     */
    public function listeStructure(Request $request) {
        $id = $request->query->get('typeStructure');
        $type = $request->query->get('type','ALL');
        return $this->structureManager->lesStructures($this->getUser(),$id, $type);
    }
}

?>