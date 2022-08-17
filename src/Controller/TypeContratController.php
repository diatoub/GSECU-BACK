<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\TypeContrat;
use FOS\RestBundle\Controller\Annotations as Rest;


class TypeContratController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_type_contrat", name="les_type_contrat")
     * @QMLogger(message="listes types contrats")
     */
    public function listeTypeContrat() {
        return $this->sendResponse(true, 200,$this->em->getRepository(TypeContrat::class)->listeTypeContrat());
    }
}

?>