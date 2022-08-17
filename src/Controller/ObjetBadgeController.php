<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\ObjetBadge;
use FOS\RestBundle\Controller\Annotations as Rest;


class ObjetBadgeController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_objet_badge", name="les_objet_badge")
     * @QMLogger(message="listes objet badge")
     */
    public function listeObjetBadge() {
        return $this->sendResponse(true, 200,$this->em->getRepository(ObjetBadge::class)->lesObjetBadges());
    }
}

?>