<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\TypeBadge;
use FOS\RestBundle\Controller\Annotations as Rest;


class TypeBadgeController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_type_badge", name="les_type_badge")
     * @QMLogger(message="listes types badges")
     */
    public function listeTypeBadge() {
        return $this->sendResponse(true, 200,$this->em->getRepository(TypeBadge::class)->listeTypeBadge());
    }
}

?>