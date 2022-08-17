<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Model\TypeDossierManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class TypeDossierController extends BaseController {

    protected $em;
    protected $typeDossierManager;
    public function __construct(ManagerRegistry $doctrine, TypeDossierManager $typeDossierManager) 
    {
        $this->em = $doctrine->getManager();
        $this->typeDossierManager = $typeDossierManager;
    }


    /**
     * @Rest\Get("/les_types_dossiers", name="les_types_dossiers")
     * @QMLogger(message="listes types dossiers")
     */
    public function listeTypeDossier(Request $request) {
        $id=$request->query->get('id');
        return $this->typeDossierManager->lesTypesDossiers($this->getUser(),$id);
    }
    
}

?>