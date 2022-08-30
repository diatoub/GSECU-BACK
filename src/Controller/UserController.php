<?php
namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
Use App\Annotation\QMLogger;
use App\Entity\User;
use App\Model\UserManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController {

    protected $em;
    protected $userManager;
    public function __construct(ManagerRegistry $doctrine, UserManager $userManager) 
    {
        $this->em = $doctrine->getManager();
        $this->userManager = $userManager;
    }


    /**
     * @Rest\Get("/les_users", name="les_users")
     * @QMLogger(message="listes de tous les utilisateurs")
     */
    public function listeAllUser(Request $request) {
        $page=$request->query->get('page',1);
        $limit=$request->query->get('limit',$_ENV['LIMIT']);
        $filtre = $request->query->get('filtre','');
        return $this->userManager->listeAllUser($this->getUser(),$page, $limit, $filtre);
    }
    
}

?>