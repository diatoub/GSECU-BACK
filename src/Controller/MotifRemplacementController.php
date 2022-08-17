<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\MotifRemplacement;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class MotifRemplacementController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_motifs_remplacements", name="les_motifs_remplacements")
     * @QMLogger(message="listes motifs remplacements")
     */
    public function listeMotifRemplacement(Request $request) {
        $type = $request->query->get('type','ALL');
        return $this->sendResponse(true, 200,$this->em->getRepository(MotifRemplacement::class)->lesMotifsRemplacements($type));
    }
}

?>