<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\MotifDemande;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class MotifDemandeController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_motifs_demandes", name="les_motifs_demandes")
     * @QMLogger(message="listes motifs demandes")
     */
    public function listeMotifDemande(Request $request) {
        $type = $request->query->get('type','ALL');
        return $this->sendResponse(true, 200,$this->em->getRepository(MotifDemande::class)->lesMotifsDemandes($type));
    }
}

?>

