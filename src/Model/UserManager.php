<?php
namespace App\Model;

use App\Entity\User;
use App\Model\Base\BaseManager;
use Doctrine\Persistence\ManagerRegistry;

class UserManager extends BaseManager {

    protected $em;
    protected $dossierMapping;
    protected $fonctions;
    protected $generateUtils;
    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->em = $doctrine->getManager();
    }

    public function listeAllUser($userConnect,$page, $limit, $filtre){
        $offset=$limit!='ALL'?($page - 1) * $limit:$_ENV["LIMIT"];
        $les_users = $this->em->getRepository(User::class)->listeAllUser($limit, $offset, $filtre);
        $total = $this->em->getRepository(User::class)->countAllUser($limit, $offset, $filtre);
        return $this->sendResponse(true, 200, $les_users, $total);
    }
}

?>