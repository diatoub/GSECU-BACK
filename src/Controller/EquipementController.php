<?php
namespace App\Controller;

use App\Model\EquipementManager;
use Symfony\Component\HttpFoundation\Request;
Use App\Annotation\QMLogger;
use FOS\RestBundle\Controller\Annotations as Rest;


class EquipementController extends BaseController {

    protected $equipementManager;
    public function __contruct( EquipementManager $equipementManager) 
    {
        $this->equipementManager = $equipementManager;
    }

    /**
     * @Rest\Post("/addEquipement", name="addEquipement")
     * @QMLogger(message="Ajouter un équipement")
     */
    public function addEquipement(Request $request ) {
        $data = json_decode($request->getContent(), true);
    }
}

?>