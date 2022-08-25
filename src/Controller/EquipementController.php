<?php
namespace App\Controller;

use App\Model\EquipementManager;
use Symfony\Component\HttpFoundation\Request;
Use App\Annotation\QMLogger;
use FOS\RestBundle\Controller\Annotations as Rest;


class EquipementController extends BaseController {

    protected $equipementManager;
    public function __construct( EquipementManager $equipementManager) 
    {
        $this->equipementManager = $equipementManager;
    }

    /**
     * @Rest\Get("/les_equipements", name="les_equipements")
     * @QMLogger(message="listes Equipement")
     */
    public function listeEquipement(Request $request) {
        $page=$request->query->get('page',1);
        $limit=$request->query->get('limit',$_ENV['LIMIT']);
        $filtre=$request->query->get('filtre','');
        return $this->equipementManager->lesEquipement($page,$limit,$this->getUser(),$filtre);
    }

    /**
     * @Rest\Post("/addEquipement", name="addEquipement")
     * @QMLogger(message="Ajouter un équipement")
     */
    public function addEquipement(Request $request ) {
        $data = json_decode($request->getContent(), true);
        return $this->equipementManager->addEquipement($data, $this->getUser(),'add');
    }
    /**
     * @Rest\Post("/editEquipement", name="editEquipement")
     * @QMLogger(message="Edité un équipement")
     */
    public function editEquipement(Request $request ) {
        $data = json_decode($request->getContent(), true);
        return $this->equipementManager->addEquipement($data, $this->getUser(),'edit');
    }

    /**
     * @Rest\Delete("/deleteEquipement/{id}", name="delete_equipement")
     * @QMLogger(message="suppression equipement")
     */
    public function deleteEquipement($id)
    {
        return $this->equipementManager->deleteEquipement($id,$this->getUser());
    }
}

?>