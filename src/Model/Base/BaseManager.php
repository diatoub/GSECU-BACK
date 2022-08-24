<?php

namespace App\Model\Base;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseManager
{
    const ADD = 'add';
    const EDIT = 'edit';
    const AJOUTE = 'ajouté';
    const UPDATE = 'mis à jour';
    const CODE = 'code';
    const SUCCESS = 'success';
    const MESSAGE = 'message';
    const DATA = 'data';
    protected $copy = array('salifabdoul.sow1@orange-sonatel.com','ababacar.fall@orange-sonatel.com','fode.ndiaye@orange-sonatel.com','malick.coly1@orange-sonatel.com','MelchisedeckFolloh.MABIALA@orange-sonatel.com','Mohamed.SALL@orange-sonatel.com');
    protected $em;
    protected $validator;
    protected $encoder;

    public function __construct(ManagerRegistry $doctrine,ValidatorInterface $validator, UserPasswordEncoderInterface $encoder){
        $this->em = $doctrine->getManager();
        $this->validator =$validator;
        $this->encoder=$encoder;
    }

    public function sendResponse($suc, $cod, $data,$total=null) {
        $retour=array(self::SUCCESS=>$suc, self::CODE=>$cod, self::DATA=>$data);
        $total!=null?$retour['total']=$total:'';
        return new JsonResponse($retour);
    }

    public function sendResponsePagination($data,$total){
        return count($data)>0?
            $this->sendResponse(true, 200, $data,$total):
            $this->sendResponse(true, 200, $data)
            ;

    }
}

?>
