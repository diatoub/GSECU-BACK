<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class BaseController extends AbstractFOSRestController
{

    public function sendResponse($suc, $cod, $data,$total=null) {
        $retour=array('success'=>$suc, 'code'=>$cod, 'data'=>$data);
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
