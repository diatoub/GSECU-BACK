<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends BaseController {

    protected $em;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }


    /**
     * @Rest\Get("/les_users", name="les_users")
     * @QMLogger(message="listes de tous les utilisateurs")
     */
    public function listeAllUser() {
        return $this->sendResponse(true, 200,$this->em->getRepository(User::class)->listeAllUser());
    }
    
}

?>