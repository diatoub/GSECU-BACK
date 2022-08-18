<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\Site;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_sites", name="les_sites")
     * @QMLogger(message="listes des sites")
     */
    public function listeSite(Request $request) {
        $type = $request->query->get('type','ALL');
        return $this->sendResponse(true, 200,$this->em->getRepository(Site::class)->lesSites($type));
    }
}

?>