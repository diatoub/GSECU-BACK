<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\CategorieDossier;
use FOS\RestBundle\Controller\Annotations as Rest;


class CategorieController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_categories", name="les_categories")
     * @QMLogger(message="listes categories")
     */
    public function listeCategorie() {
        return $this->sendResponse(true, 200,$this->em->getRepository(CategorieDossier::class)->lescategories());
    }
}

?>